import google.generativeai as genai
import os
import sys
import json

# Ensure the API key is set as an environment variable
os.environ["API_KEY"] = "AIzaSyBgO8noquDwoAZwLqDFQ_HbMmGi1Ovk1HM"  # Replace with your actual API key

# Initialize the Gemini model
genai.configure(api_key=os.environ["API_KEY"])
model = genai.GenerativeModel('gemini-1.5-flash')

# Get the user input from command line arguments
user_input = sys.argv[1]

# Generate content based on the user input
response = model.generate_content(user_input)

# Print the response as JSON
print(json.dumps(response, indent=2))
