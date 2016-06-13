import sys
import select
import tty
import termios
import RPi.GPIO as GPIO
import time
import datetime
import MySQLdb
import uinput
import os
import shutil
import io

#set up access to the mysql database using root due to permissions confusion, once permissions are fixed master may be used
db=MySQLdb.connect(host="localhost", user="root", passwd="password", db="doorMaster")
#add cursor which allows interaction with db
curs=db.cursor()
#don't even worry about this one
count=0
#save terminal settings so they can be reset after setting terminal to character mode
old_settings = termios.tcgetattr(sys.stdin.fileno())
#returns true if there is data int he sys.stdin buffer
def isData():
    return select.select([sys.stdin], [],[], 0) ==([sys.stdin], [], [])
#compares two time stamps to a second
def compareTimeStamps(time1, time2):
    year=[int(time1[0:4]), int(time2[0:4])]
    month=[int(time1[5:7]),int(time2[5:7])+(year[1]-year[0])*12]
    day=[int(time1[8:10]),int(time2[8:10])+(month[1]-month[0])*30]
    hour=[int(time1[11:13]),int(time2[11:13])+(day[1]-day[0])*24]
    minute=[int(time1[14:16]),int(time2[14:16])+(hour[1]-hour[0])*60]
    second=[int(time1[17:19]),int(time2[17:19])+(minute[1]-minute[0])*60]
    timeDifference=second[1]-second[0]
    return timeDifference
#returns the time of teh last status update
def getStatusTime():
    curs.execute("SELECT timestamp FROM status")
    readStat=curs.fetchall()
    time=readStat[0]
    return time
#gets current time in milliseconds
def getTime():
    millis = int(round(time.time() * 1000))
    return millis
#watches incoming data and times exit for complete card reads
def watchData():
    print("in watch data")
    cardReadData=""
    startReadTime=None#time reading started
    waitTimeMS=500#how long to wait if the buffer is empty
    timestamp=None#current timestamp
    isReading=False#true when readng is in progress
    deviceStoppedReadingTS=None#timestamp of when data buffer went empty after read started
    continueRead=True#true when reading should progress
    mysqlTS=getTime()#timestamp for use in logs
    updateStatus("watching for data")#update the status table to reflet current action
    try:
        tty.setcbreak(sys.stdin.fileno())#set teh stdin buffer to character read
        while(continueRead):
	    if(getTime()-mysqlTS>=10000):
		mysqlTS=getTime()
		updateStatus("in data watch cycle")
            if(isData() and not isReading):#if there is data in the buffer and the read has not started (e.g. data is in buffer for first time this cycle)
                #print("There is dta and we have not started reading")
                startReadTime=getTime()
                isReading=True
                deviceStoppedReadingTS=None
                cardReadData=sys.stdin.read(1)
            elif(isData() and isReading):#if there is data and the read cycle has already begun (e.g. reading data after the first character)
                deviceStoppedReadingTS=None
                #print("There is data and we have already started reading")
                cardReadData+=sys.stdin.read(1)
            elif(not isData() and isReading):#read has started and there is no data in the buffer (e.g. data was in the buffer but all data in the buffer has been read, note this does not always mean that the full read has been completed)
                #print("There is no data and we have previously started reading")
                if(deviceStoppedReadingTS==None):#if this is the first time there has been an empty buffer since we started reading add a new timestamp
                    #print("There was no time set")
                    deviceStoppedReadingTS=getTime()
                elif(getTime()-deviceStoppedReadingTS>waitTimeMS):#if this is not the first time the buffer has been empty, and it's been longer than waittime since the buffer emptied end the loop
                    #print("we have hit the maximum wait time and are now done")
                    isReading=False
                    print("Data read: "+cardReadData)
                    deviceStoppedReadingTS=None
                    continueRead=False
    finally:#reset the terminal
        termios.tcsetattr(sys.stdin, termios.TCSADRAIN, old_settings)
        return cardReadData
    
#deprecated function kept for reference
def watchData2():
    global count
    card=""
    count=count+1
    events=(uinput.KEY_E, uinput.KEY_H, uinput.KEY_L, uinput.KEY_O)
    device=uinput.Device(events)
    device.emit_click(uinput.KEY_H)
    try:
	updateStatus("Waiting for input")
        tty.setcbreak(sys.stdin.fileno())
        reading=True
        readStarted=False
        print("now watching input stream sys.stdin, data at time 0: "+str(isData()))
        print("reading: "+str(reading))
        print("read started: "+str(readStarted))
	#continually look for data
        while reading:
	    curs.execute("SELECT * FROM acceptedCards") 
	    #if there is data say so, start making the card ID
	    if(readStarted):
                timeDif=compareTimeStamps(getStatusTime(), make_timestamp())
		print(timeDif)
		if(timeDif>2):
                    reading=False
            if isData():
                print("data found")
		#if this is the first time data is being collected in this cycle sleep to allow the buffer to fill
                if not readStarted:
                    print("read has started, sleeping to allow buffer filling")
		    updateStatus("Reading card")
                    #time.sleep(1)
		#if data has already started to compile just keep going            
                readStarted=True
                a=sys.stdin.read(21)
                print("read in: "+str(a))            
                card+=a
	    #if data has already been read and there is no more stop scanning and exit loop
            elif readStarted and not isData():
                print("reading complete data remaining: "+str(isData()))
                reading=False
    #return card and reset terminal to old settings, also switch dumbVar so the program knows if the cycle is even or odd
    finally:
        print("resetting terminal submitting card")
	termios.tcflush(sys.stdin, termios.TCIFLUSH)
        termios.tcsetattr(sys.stdin, termios.TCSADRAIN, old_settings)
	return card

def updateStatus(message):
    timestamp=make_timestamp()
    curs.execute("DELETE FROM status WHERE True=True")
    curs.execute("INSERT INTO status(status, timestamp) VALUES ('"+message+"', '"+ timestamp+"')")
    db.commit()

#get data from the card, truncate it, and then pass it to the handler function
def get_scan():
    card=watchData()
    if(card!=None):
        print("\nresult: "+card)
        handle_card(card)
    else:
        print("Bad read restarting")

#trigger GPIO pins to unlock the door
def open_door():
    updateStatus("Good read")
    GPIO.setmode(GPIO.BOARD)
    GPIO.setup(12,GPIO.OUT)
    GPIO.output(12,GPIO.LOW)
    GPIO.output(12,GPIO.HIGH)
    time.sleep(5)
    #os.remove("/var/www/Testing/devTest/androidM/index.html")
    open("/var/www/Testing/devTest/androidM/index.html", 'w').close()
    shutil.copyfile("/var/www/Testing/devTest/home.html", "/var/www/Testing/devTest/androidM/index.html")
    GPIO.cleanup()

#make a new timestamp
def make_timestamp():
    ts=time.time()
    st=datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')
    return st

#check the card against the table of accepted cards to see if access is allowed, open door if allowed and log door openings
def check_card(card):
    curs.execute("SELECT * FROM acceptedCards")
    goodRead="door opened for: "
    for reading in curs.fetchall():
        if reading[0] == card:
            timestampAdd=make_timestamp()
            openedFor="Door opened for "+reading[1]
            curs.execute("INSERT INTO log(accessRequest, action, timestamp) VALUES ('"+card+"', '"+openedFor+"', '"+timestampAdd+"')")
            db.commit()
            #os.remove("/var/www/Testing/devTest/androidM/index.html")
	    open("/var/www/Testing/devTest/androidM/index.html", 'w').close()
            shutil.copyfile("/var/www/Testing/devTest/accessGranted.html", "/var/www/Testing/devTest/androidM/index.html")
            return True
	
#check the card, if it's allowed open the door, if it's not then log an invalid entry attempt in the log
def handle_card(card):
    if check_card(card):
        open_door()
        time.sleep(1)#Change back to home page after auto refresh
    else:
        #os.remove("/var/www/Testing/devTest/androidM/index.html")
	open("/var/www/Testing/devTest/androidM/index.html", 'w').close()
	shutil.copyfile("/var/www/Testing/devTest/accessDenied.html", "/var/www/Testing/devTest/androidM/index.html")
	timestampAdd=make_timestamp()
	curs.execute("INSERT INTO log (accessRequest, action, timestamp) VALUES ('"+card+"', 'Invalid ID - Door Not Opened', '"+timestampAdd+"')")
	updateStatus("Bad read")
	db.commit()
	time.sleep(1)#Change back to home page after auto refresh
    	#os.remove("/var/www/Testing/devTest/androidM/index.html")
	open("/var/www/Testing/devTest/androidM/index.html", 'w').close()
    	shutil.copyfile("/var/www/Testing/devTest/home.html", "/var/www/Testing/devTest/androidM/index.html")
    db.commit()

#never don't not stop scanning
def startProc():
    while True:
        get_scan()

startProc()




    
 
