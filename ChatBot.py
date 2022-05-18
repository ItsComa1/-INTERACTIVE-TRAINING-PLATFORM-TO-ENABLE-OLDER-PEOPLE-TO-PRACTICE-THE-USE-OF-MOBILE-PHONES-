#! /usr/bin/env python3
import smtplib
import imaplib
import email
import threading
from threading import Thread
import time
import re
from email import policy
from twilio.rest import Client
import keys
import mysql.connector
from multiprocessing import Process
from flask import Flask, request, redirect, Response, session
from twilio.twiml.messaging_response import MessagingResponse
from twilio.twiml.voice_response import VoiceResponse, Gather

# for validating an Email
regex = r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b'

#Variables to initialise 
str_ = ''
SECRET_KEY = 'a secret key'
app = Flask(__name__)
app2 = Flask(__name__)
app.config.from_object(__name__)
app2.config.from_object(__name__)
#app.secret_key = b'_5#y2L"F4Q8z\n\xec]/'

def send_email(user_email, email_msg):

	#Log onto sending email address and send email to email that sent message to us
	print('Sent email to '+user_email)
	server = smtplib.SMTP('smtp.gmail.com', 587)
	server.starttls()
	server.login('phpserver14@gmail.com', 'toothpaste465')
	server.sendmail('phpserver14@gmail.com',
			user_email,
			email_msg,
			)

def listen_email():

	#Stores previous email
	prev_email_id =  None
	#Stores initial response value on startup
	response = ''

	while(True):
			#Retrieving email from inbox
			#------------------------------------------------------------------------------------------------------------
			#your code here
			print('\nStarting Email Application')
			#logs in to the desired account and navigates to the inbox
			mail = imaplib.IMAP4_SSL('imap.gmail.com')
			mail.login('phpserver14@gmail.com','toothpaste465')
			mail.list()
			mail.select('inbox')
			result, data = mail.search(None,'ALL')

			#retrieves the latest (newest) email by sequential ID
			ids = data[0]
			id_list = ids.split()
			latest_email_id = id_list[-1]

			#Get body of email
			result, data = mail.fetch(latest_email_id, "(RFC822)")
			body_email = data[0][1]

			#Get Subject of mail
			msg = email.message_from_bytes(body_email)
			emailDate =  msg["Date"]
			emaiSubject = msg["Subject"]
			emaiSubject_Upper = emaiSubject.upper() #Converts what user enter to upper case only
			if msg.is_multipart():
				emailMain = str(msg.get_payload(0))
			else:
				print(msg.get_payload(None,True))

			#Retrieve email body contents
			emailBod = emailMain.replace('Content-Type: text/plain; charset="UTF-8"','')
			emailBody = emailBod.strip()
			emailBody_Upper = emailBody.upper()
			#print(emailBody_Upper)

			#------------------------------------------------------------------------------------------------------------


			#Retrieving senders email details to send email response
			#------------------------------------------------------------------------------------------------------------
			
			#Get senders email address
			result, data = mail.fetch(latest_email_id, "(BODY[HEADER.FIELDS (FROM)])")

			#Get raw data
			raw_email = data[0][1] # here's the body, which is raw text of the whole email
			# including headers and alternate payloads

			#Convert raw email bytes to string
			str_email = str(raw_email)

			#Extract email address from string
			email_Extract = re.search(r'[\w\.-]+@[\w\.-]+(\.[\w]+)+',str_email)
			user_email = email_Extract.group()
			print(user_email)

			email_str = str(user_email)

			#If a new email is received goto response function
			if prev_email_id != latest_email_id:
				iscall = False
				response = smartresponse(emailBody_Upper, email_str, iscall)

			print(response)

			#Get Name of user from database by checking which user has the email address which sent the email
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("select username from users where email='"+email_str+"'")
			for r in mycursor:
				str_ = ''.join(r)

			email_msg = "From: PyBot: hi@hi.com\nSubject: <PyBot>\n\n" + "Hello " + str_ + response

			#print(email_msg)

			#If a new email is received send an email
			if prev_email_id != latest_email_id:
				send_email(user_email, email_msg)

			#Set prev email id to latest emails id (initially prev email id is 0)
			prev_email_id = latest_email_id

			#------------------------------------------------------------------------------------------------------------

			print("There will now be 12 second delay before this application runs again")

			#Delay 12 seconds
			time.sleep(12)

@app.route("/sms", methods=['GET', 'POST'])
def sms_reply():

	#global str_, counter, counter2
	# Use this data in your application logic
	from_number = request.form['From']
	to_number = request.form['To']
	body = request.form['Body']
	body_uppercase = body.upper()
	print(body_uppercase)
	print(from_number)
	number = re.sub('whatsapp:', '', from_number)
	print(number)

	#Get Name of user from database by checking which user has the mobile number which sent twilio a message
	mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
	mycursor = mydb.cursor()
	mycursor.execute("use usersignup")
	mycursor.execute("select username from users where phone="+number)

	last_str = str_
	print(last_str)

	for r in mycursor:
		str_ = ''.join(r)

	"""Respond to incoming calls with a simple text message."""
	# Start our TwiML response
	resp = MessagingResponse()

	iscall = False
	response = smartresponse(body_uppercase, number, iscall)

	# Add a message
	resp.message("Hello " + str_ + response)

	return Response(str(resp), mimetype="application/xml")

@app2.route("/voice", methods=['GET', 'POST'])
def voice():
	"""Respond to incoming phone calls with a menu of options"""
	# Start our TwiML response
	resp = VoiceResponse()

	# If Twilio's request to our app included already gathered digits,
	# process them
	if 'SpeechResult' in request.values:
		# Get which digit the caller chose
		choic = request.values['SpeechResult']
		choice = choic.upper()
		print(choice)

		# Use this data in your application logic
		from_number = request.form['From']
		print(from_number)
		#number = re.sub('whatsapp:', '', from_number)
		#print(number)

		iscall = True
		response = smartresponse(choice.strip("."), from_number, iscall)

		print(response)

		#Get Name of user from database by checking which user has the mobile number which sent twilio a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select username from users where phone="+from_number)
		for r in mycursor:
		        str_ = ''.join(r)


		resp.say(str_ + response)

	# Start our <Gather> verb
	gather = Gather(num_digits=1, input='speech dtmf')
	gather.say('Hello im pybot you can have a conversation with me')
	resp.append(gather)

	# If the user doesn't select an option, redirect them into a loop
	resp.redirect('/voice')

	return str(resp)

def smartresponse(msg, user_from, iscall):


	greeting = ['HELLO', 'HI', 'YO', 'WHATS UP']
	goodbye = ['GOODBYE', 'BYE', 'SEE YOU', 'SEE YOU LATER']
	joke = ['JOKE', 'TELL ME A JOKE', 'TELL ME SOMETHING FUNNY']

	#COUNTER GREETIMG
	#Check if msg came from an email
	if(re.fullmatch(regex, user_from)):
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter from users where email='"+user_from+"'")
		for r in mycursor:
			num = int(''.join(map(str, r)))
		print("Email count",num)
	if not iscall:
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter2 from users where phone='"+user_from+"'")
		for r in mycursor:
			num = int(''.join(map(str, r)))
		print("Phone count ",num)

	else:
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter3 from users where phone='"+user_from+"'")
		for r in mycursor:
			num = int(''.join(map(str, r)))
		print("Phone count ",num)

	#COUNTER GREETING END


	#COUNTER GOODBYE
	#Check if msg came from an email
	if(re.fullmatch(regex, user_from)):
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter4 from users where email='"+user_from+"'")
		for r in mycursor:
			num2 = int(''.join(map(str, r)))
		print("Email count",num2)
	if not iscall:
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter5 from users where phone='"+user_from+"'")
		for r in mycursor:
			num2 = int(''.join(map(str, r)))
		print("Phone count ",num2)
	else:
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter6 from users where phone='"+user_from+"'")
		for r in mycursor:
			num2 = int(''.join(map(str, r)))
		print("Phone count ",num2)

	#COUNTER GOODBYE END

	#COUNTER Joke
	#Check if msg came from an email
	if(re.fullmatch(regex, user_from)):
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter7 from users where email='"+user_from+"'")
		for r in mycursor:
			num3 = int(''.join(map(str, r)))
		print("Email count",num3)
	if not iscall:
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter8 from users where phone='"+user_from+"'")
		for r in mycursor:
			num3 = int(''.join(map(str, r)))
		print("Phone count ",num3)
	else:
		#Get Name of user from database by checking which user has the email which sent a message
		mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
		mycursor = mydb.cursor()
		mycursor.execute("use usersignup")
		mycursor.execute("select counter9 from users where phone='"+user_from+"'")
		for r in mycursor:
			num3 = int(''.join(map(str, r)))
		print("Phone count ",num3)

	#COUNTER Joke END


	if msg in greeting and num < 2:
		#COUNTER INCREMENT START
		#Check if msg came from an email
		if(re.fullmatch(regex, user_from)):
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter = counter+1 where email='"+user_from+"'")
			mydb.commit()
		if not iscall:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter2 = counter2+1 where phone='"+user_from+"'")
			mydb.commit()

		else:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter3 = counter3+1 where phone='"+user_from+"'")
			mydb.commit()

		#COUNTER INCREMENT END
		response = ' I hope you are well'
		return response

	elif msg in greeting and num >=2:
		#COUNTER SET VALUE 0 START
		#Check if msg came from an email
		if(re.fullmatch(regex, user_from)):
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter = 0 where email='"+user_from+"'")
			mydb.commit()
		if not iscall:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter2 = 0 where phone='"+user_from+"'")
			mydb.commit()
		else:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter3 = 0 where phone='"+user_from+"'")
			mydb.commit()

		#COUNTER SET VALUE 0 END
		response = ' you messaged hello 3 times ;)'
		return response




	elif msg in goodbye and num2 < 2:
		#COUNTER INCREMENT START
		#Check if msg came from an email
		if(re.fullmatch(regex, user_from)):
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter4 = counter4+1 where email='"+user_from+"'")
			mydb.commit()
		if not iscall:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter5 = counter5+1 where phone='"+user_from+"'")
			mydb.commit()

		else:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter6 = counter6+1 where phone='"+user_from+"'")
			mydb.commit()

		#COUNTER INCREMENT END
		response = ' i will see you later'
		return response



	elif msg in goodbye and num2 >=2:
		#COUNTER SET VALUE 0 START
		#Check if msg came from an email
		if(re.fullmatch(regex, user_from)):
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter4 = 0 where email='"+user_from+"'")
			mydb.commit()
		if not iscall:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter5 = 0 where phone='"+user_from+"'")
			mydb.commit()
		else:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter6 = 0 where phone='"+user_from+"'")
			mydb.commit()

		#COUNTER SET VALUE 0 END
		response = ' you messaged bye 3 times ;)'
		return response






	elif msg in joke and num3 < 2:
		#COUNTER INCREMENT START
		#Check if msg came from an email
		if(re.fullmatch(regex, user_from)):
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter7 = counter7+1 where email='"+user_from+"'")
			mydb.commit()
		if not iscall:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter8 = counter8+1 where phone='"+user_from+"'")
			mydb.commit()

		else:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter9 = counter9+1 where phone='"+user_from+"'")
			mydb.commit()

		#COUNTER INCREMENT END
		response = ' did you hear about the mathematician who is afraid of negative numbers? He will stop at nothing to avoid them'
		return response



	elif msg in joke and num3 >=2:
		#COUNTER SET VALUE 0 START
		#Check if msg came from an email
		if(re.fullmatch(regex, user_from)):
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter7 = 0 where email='"+user_from+"'")
			mydb.commit()
		if not iscall:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter8 = 0 where phone='"+user_from+"'")
			mydb.commit()
		else:
			#Get Name of user from database by checking which user has the mobile number which sent twilio a message
			mydb = mysql.connector.connect(host="192.168.1.18", user="admin", passwd="the_secure_password")
			mycursor = mydb.cursor()
			mycursor.execute("use usersignup")
			mycursor.execute("update users set counter9 = 0 where phone='"+user_from+"'")
			mydb.commit()

		#COUNTER SET VALUE 0 END
		response = ' you messaged joke 3 times ;)'
		return response



	else:
		response = ' my name is Pybot you can can ask me generic questions and I will attempt to help you'
		return response


# With Multi-Threading Apps, YOU CANNOT USE DEBUG!
# Though you can sub-thread.
def runFlaskApp1():
	app.run(host='127.0.0.1', port=5000, debug=False, threaded=True)

def runFlaskApp2():
	app2.run(host='127.0.0.1', port=5001, debug=False, threaded=True)


if __name__ == '__main__':
	#p1 = Process(target=listen_email)
	#p1.start()
	#app_context().push()

	#p2 = Process(target=app.run(debug=False, threaded=True))
	#p2.start()

	#p3 = Process(target=app2.run(port=5001, debug=False, threaded=True))
	#p3.start()

	#p1.join()
	#p2.join()
	#p3.join()
	#app.run(debug=True)


	#Thread(target = listen_email).start()
	#Thread(target = app.run(debug=False)).start()
	#Thread(target = app2.run(debug=False).start()

	#Thread(target = listen_email).start()


	# Executing the Threads seperatly.
	t1 = threading.Thread(target=runFlaskApp1)
	t2 = Thread(target=runFlaskApp2)
	t3 = Thread(target=listen_email)
	t1.start()
	t2.start()
	t3.start()
