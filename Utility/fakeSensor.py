import requests, json, time
from random import randrange
from datetime import datetime

apiUrl = "http://localhost:5000"
token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJlc3Atand0Iiwic3ViIjoxLCJpYXQiOjE2MDg4MDY1NTcsImV4cCI6NDc2MjQwNjU1Nywib3duZWQiOlsxLDJdfQ.XleaJKATjDun4pn6GLq9vHJAfkMkPLUOqV12eyL2Mew"
headers = { 'Authorization' : 'Bearer ' + token }

while True:
    data = {
        "sensorId": 1,
        "sensorValue": randrange(25),
        "sensorDatetime": datetime.now().strftime("%Y-%m-%d-%H:%M:%S")
    }

    r = requests.put(apiUrl + '/sensors/data', json=data, headers=headers)

    if r.status_code != 200:
        print(r.text)

    time.sleep(5)