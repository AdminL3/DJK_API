import requests
import config
import json

base = "https://djksbm.org/hauptverein-neu/"
base = "http://localhost/plugin-test/"
url = f"{base}wp-json/djk-api/update"

token = config.WP_TOKEN

with open("mock.json", "r", encoding="utf-8") as f:
    content = json.load(f)


headers = {
    "Authorization": f"Bearer {token}",
    "Content-Type": "application/json"
}

data = {
    'content': content
}

try:
    response = requests.post(
        url,
        json=data,
        headers=headers
    )
    response.raise_for_status()
    print(f"Snippet for team {data["content"]["name"]} created/updated successfully.")
    print(response.json())
except requests.exceptions.RequestException as e:
    print(f"An error occurred while updating snippet for team {data["content"]["name"]}: {e}")
    print(response.json())
