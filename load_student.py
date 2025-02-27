import requests
import mysql.connector
import json

# Connect to the database
mydb = mysql.connector.connect(
  host="sv94.ifastnet.com",
  user="synergyc",
  password="synergy@central",
  database="synergyc_student_registration1"
)
cursor = mydb.cursor()

# Get the data from the daatabase
cursor.execute("SELECT * FROM student WHERE s_status = 'ACTIVE'")
result = cursor.fetchall()

for row in result:
    #request to the API
    url = "http://127.0.0.1:8000/api/students/"
    headers = {
        "Content-Type": "application/json"
    }
    
    if(row[39] =="Programming"):
        course_id = 1
    elif(row[39]=="Networking"):
        course_id = 2
    elif(row[39]=="Multimedia"):
        course_id = 3
    elif(row[39]=="Electronics"):
        course_id = 4
    else:
        course_id = 5
    
    
    payload = {
        "name": row[2],
        "email": row[3],
        "password": "synergyexam",
        "course_id": course_id,
        "ic": row[4],
        "gender": row[21],
    }
    response = requests.post(url, headers=headers, data=json.dumps(payload))    
    
    print(response.text)
