#criar chave API no site: https://aistudio.google.com/app/apikey
#rodar os códigos fora do python:
#pip install -q -U google-generativeai
#export API_KEY=<YOUR_API_KEY>

import google.generativeai as genai
import os

genai.configure(api_key=os.environ["API_KEY"])

model = genai.GenerativeModel('gemini-1.5-flash')

response = model.generate_content( "what is the most common translation for the word heart to portuguese and spanish in a medical context? the response must be between parenthesis ")
print(response.text)
