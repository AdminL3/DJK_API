import requests
import config
import json

base = "http://localhost/plugin-test/"
base = "https://djksbm.org/hauptverein-neu/"
url = f"{base}wp-json/djk-api/update"

token = config.WP_TOKEN

snippet_id = "test"

with open("mock.json", "r", encoding="utf-8") as f:
    content = json.load(f)


headers = {
    "Authorization": f"Bearer {token}",
    "Content-Type": "application/json"
}

data = {
    'snippet_id': snippet_id,
    'content': content
}

try:
    response = requests.post(
        url,
        json=data,
        headers=headers
    )
    response.raise_for_status()
    print(f"Snippet for team {snippet_id} created/updated successfully.")
    print(response.json())
except requests.exceptions.RequestException as e:
    print(f"An error occurred while deleting snippet for team {
          snippet_id}: {e}")
    print(response.json())
