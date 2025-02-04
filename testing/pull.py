import json
import requests

url = "http://localhost/plugin-test/wp-json/djk_api/get"
data = {'name': "U18"}

try:
    response = requests.post(url, json=data)
    response.raise_for_status()
except Exception as e:
    print(f"An error occurred: {e}")
print(response.json())
if response.status_code == 200:
    with open("Testing.json", "w") as file:
        json.dump(response.json(), file, indent=4)
