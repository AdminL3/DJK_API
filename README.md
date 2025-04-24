# RESTful-Snippets

- Plugin that opens an API endpoint to change the database
- This database can then be accessed using the Wordpress Snippets
- The API endpoint is protected by a secret token
- Which can be generated using the Plugins settings

### 1. Generate a token

### 2. Access the API endpoint using the token

##### For Python:

Install requests using

```
pip install requests

or

pip install -r requirements.txt
```

- Change the Token in the config.py
- And your page URL in the script
- Run the script

### 3. Use the Wordpress Snippet

- Use Wordpress snippet on your wordpress page:

[snippet id="your-specified-id"]
