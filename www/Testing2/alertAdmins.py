import time
import subprocess
import smtplib
import MySQLdb
import string
def mainExec():
    db=MySQLdb.connect(host="localhost", user="root", passwd="password", db="doorMaster")
#add cursor which allows interaction with db
    curs=db.cursor()
    server=smtplib.SMTP('smtp.gmail.com', 587)
    server.ehlo()
    server.starttls()
    server.ehlo
    #Log into email account set up for door
    server.login("exampleEmail@gmail.com", "password")#replace this information with your own email account that you plan to use for alerts
    curs.execute("SELECT name FROM accessRequests")
    namesOut=curs.fetchall()
    name=namesOut[len(namesOut)-1]
    curs.execute("SELECT email FROM accessRequests")
    emailsOut=curs.fetchall()
    email=emailsOut[len(emailsOut)-1]
    sendArray=[]
    #construct message to be sent including user receving, admins in cc, subject and message
    curs.execute("SELECT * FROM acceptedCards")
    for reading in curs.fetchall():
       if(reading[3]):
           sendArray.append(reading[2])
    subj=str(name)+" has requested access to the hackerspace"
    text1=str(name)+" has just requested access to the hackerspace."
    text2="If additional information is needed they can be contacted at "+str(email)+" \nTo accept this request visit http://111.111.111/accessRequests.php" #you will need to place the ip of your admin page here
    text=text1+"\n"+text2
    #construct message from different parts
    msg=string.join((
	"From: hackerspacedoor@gmail.com",
	"To: %s" % ', '.join(email),
	"CC: %s" % ', '.join(sendArray),
	"Subject: %s" % subj,
	"",
	text), "\r\n")
    server.sendmail("hackerspacedoor@gmail.com", sendArray, msg)
    action="emailed admins"
    time="now"
    db.commit()
    #subj="You have requested access to the hackerspace"
    #text="Hello "+str(name)+" you have requested card swipe access to the hackerspace. And administrator will review your request as soon as possible."
    #msg="Subject: %s\n\n%s" % (subj, text)
    #server.sendmail("hackerspacedoor@gmail.com", email, msg)
    #return("emailsent")

    server.quit()


mainExec()
print("done")
