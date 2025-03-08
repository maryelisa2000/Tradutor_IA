import csv
import ollama

# importa todos termos e seus ids para um dicionario
with open('input.csv', 'r',) as entrada:
	leitor = csv.DictReader(entrada)
	listaTermos = list(leitor)

chaves = listaTermos[0].keys()
listaTrad = []


for termo in listaTermos:
	listaTrad.append(termo['POR'])

#print(listaTrad)

# traduzir
listaDic = []
listaCsv = []
for x in listaTrad:
	ollama_response = ollama.chat(model='llama3.2', messages=[
	   {
	     'role': 'system',
	     'content': 'Me responda como um tradutor de termos científicos usando o Medical Subjects Headings (MeSH).',
	   },
	   {
	     'role': 'user',
	     'content': f"Traduza esse termo, que está em português (PT-BR): \n'{x}\n' para os seguintes idiomas na seguinte ordem: Checo;Inglês;Finlandês;Francês;Alemão;Italiano;Polonês;Russo;Espanhol;Sueco;Servo-Croata;Holandês;Letão. Responda apenas com a tradução do termo, sem outras palavras,  no seguinte modelo: '- idioma: tradução'. ",
	   },
	])

	texto = ollama_response['message']['content']
	lines = texto.strip().split("\n")

	# dicionario para o q for extraido
	extracted_data = {}
	
	listaNova = []
	
	for line in lines:
		partes = line.split(":")
		if len(partes) >= 2:
			key = partes[0].strip("- ")  # Remover ": " da chave
			value = partes[1]
			extracted_data[key] = value
			listaNova.append(value)
			
	listaDic.append(listaNova)

	# Print the extracted data
	#for key, value in extracted_data.items():
	#	print(f"{key}: {value}")

	#checo = extracted_data.get("Checo")
	#print(f"Checo: {checo}")

# ver listas no terminal
#print(listaDic)

# salva num novo csv
with open('teste.csv', 'w', newline ='') as saida:
	# lista de dicionario para csv
	fieldnames = ['Português', 'Checo', 'Inglês','Finlandês', 'Francês', 'Alemão', 
	'Italiano', 'Polonês', 'Russo', 'Espanhol', 'Sueco', 'Servo-Croata', 'Holandês', 'Letão']

	# lista para csv
	write = csv.writer(saida)
	write.writerow(fieldnames)
	write.writerows(listaDic)
