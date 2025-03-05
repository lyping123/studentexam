import requests
import mysql.connector
import json

# Connect to the database
mydb = mysql.connector.connect(
  host="sv94.ifastnet.com",
  user="synergyc",
  password="synergy@central",
  database="synergyc_attendance"
)
cursor = mydb.cursor()

# Get the data from the daatabase

cursor.execute("SELECT pp_name FROM tb_student where pp_name!='' group by pp_name")
result = cursor.fetchall()


for groupname in result:
    url = "http://127.0.0.1:8000/api/students/"
    headers = {
        "Content-Type": "application/json"
    }
    cursor.execute(f"SELECT name FROM tb_student where name='{groupname[0]}'")
    group_name=cursor.fetchone()
    
    cursor.execute(f"SELECT name FROM tb_student where pp_name='{groupname[0]}'")
    students=cursor.fetchall()
    studentList=[]
    for student in students:
        studentList.append(student[0])
    
    payload = {
        "studentNames":studentList,
        "groups":group_name
    }
    
   
    
    response = requests.post(url, headers=headers, data=json.dumps(payload))    
    
    print(response.text)
