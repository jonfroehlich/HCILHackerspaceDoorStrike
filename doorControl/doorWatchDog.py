import time
import subprocess
import smtplib
import doorIB
import MySQLdb
#runs the main door control program in a try catch and restarts the pi if the main program ever crashes
db=MySQLdb.connect(host="localhost", user="root", passwd="password", db="doorMaster")
#add cursor which allows interaction with db
curs=db.cursor()
#connects to gmail server for emailing admins if pi crashes and when it reboots
server=smtplib.SMTP('smtp.gmail.com', 587)
server.ehlo()
server.starttls()
server.ehlo
#login to a gmail account which is being used for alerts
server.login("exampleemail@gmail.com", "password")
#create reboot message
subj="Door strike has rebooted"
text="This is an email alert, the hackerspace door strike has successfully rebooted and should now be fully operational"
msg="Subject: %s\n\n%s" % (subj, text)
sendArray=[]
print(sendArray)
curs.execute("SELECT * FROM acceptedCards")
for reading in curs.fetchall():
    if(reading[3]):
        sendArray.append(reading[2])
#send reboot message
server.sendmail("exampleemail@gmail.com", sendArray, msg)
server.quit()
#run door control in a try except
try:
    doorIB.startProc()
except Exception as e:
    #if doorIB.py has crashed an error email is created and sent to admins, the pi is then restarted.
    server=smtplib.SMTP('smtp.gmail.com', 587)
    server.ehlo()
    server.starttls()
    server.ehlo
    server.login("exampleemail@gmail.com", "MakingIsFun!")
    subject="Alert - Door strike has crashed: %s" % str( e)[:20]
    text="\nHelp! I've Crashed due to %s, please make sure I am still functioning properly after self-reboot. I will now attempt to reboot myself..."%str(e)
    msg="Subject: %s\n\n%s" % (subject, text)
    sendArray=[]
    curs.execute("SELECT * FROM acceptedCards")
    for reading in curs.fetchall():
	if(reading[3]):
	    sendArray.append(reading[2])
    server.sendmail("exampleemail@gmail.com", sendArray, msg)
    server.quit()
    #restart the pi
    subprocess.call("/home/pi/doorControl/rebootPi.sh", shell=True)
