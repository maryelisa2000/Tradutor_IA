<?php

ini_set("default_socket_timeout", 6000);

?>

<!DOCTYPE html>
<html lang="pt-br" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS FILES -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <!-- JS FILES -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist/build/pdf.min.js"></script>
    <title>Analisador de artigos usando Inteligência Artificial (Llama 3.1)</title>
</head>

<body>
    <?php include_once('inc/header.php'); ?>
    <div class="container">
        <main>
            <div class="container p-5 rounded mt-5">
                <h1 class="display-5">Analisador de artigos usando Inteligência Artificial (Llama 3.1)</h1>

                <div id="app">
                    <label for="formFile" class="form-label">Recuperar Título - Não incluir https://doi.org/ </label>
                    <div class="input-group mb-3">
                        <input v-model="doi" placeholder="Insira o DOI" class="form-control">
                        <button @click="fetchTitleCrossref" class="btn btn-primary">Buscar Título na Crossref</button>
                        <button @click="fetchTitleCrossref" class="btn btn-primary">Buscar Título no Openalex</button>
                    </div>


                    <div class="mb-3">
                        <label for="formFile" class="form-label">Insira um PDF</label>
                        <input class="form-control" type="file" id="pdfInput" accept=".pdf" @change="onFileChange">
                        <button @click="extractText" class="btn btn-warning mt-4">Extrair Texto (Primeiro passo, caso
                            tenha
                            optado por PDF)</button>
                    </div>


                    <form action="resultadoOpenAI.php" method="post">

                        <div class="ml-container">
                            <label for="search" class="form-label">Ou digite título e resumo</label>
                            <textarea class="form-control" id="userInput" name="userInput" rows="10"
                                placeholder="Digite um título e resumo em português"
                                v-model="completeString"></textarea>
                            <p v-if="extractedText">Texto Extraído: {{ extractedText }}</p>

                            <button class="btn btn-primary mt-5" type="submit">Submeter</button>

                    </form>

                </div>


            </div>
        </main>
        <?php include_once('inc/footer.php'); ?>
    </div>

    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    doi: '',
                    title: '',
                    abstract: '',
                    completeString: '',
                    file: null,
                    extractedText: ''
                };
            },
            methods: {
                fetchTitleCrossref() {
                    const url = `https://api.crossref.org/works/${this.doi}`;
                    axios.get(url)
                        .then(response => {
                            this.title = response.data.message.title[0];
                            this.abstract = response.data.abstract;
                            if (response.data.abstract) {
                                this.completeString = response.data.message.title[0] + ':' + response.data
                                    .abstract;
                            } else {
                                this.completeString = response.data.message.title[0]
                            }
                        })
                        .catch(error => {
                            console.error("Erro ao buscar o título:", error);
                            this.title = "Erro ao buscar o título";
                        });
                },
                fetchTitleOpenalex() {
                    const url = `https://api.openalex.org/works/https://doi.org/${this.doi}`;
                    axios.get(url)
                        .then(response => {
                            this.title = response.openAlexRecord.data.title;
                            this.completeString = response.openAlexRecord.data.title;
                        })
                        .catch(error => {
                            console.error("Erro ao buscar o título:", error);
                            this.title = "Erro ao buscar o título";
                        });
                },
                onFileChange(event) {
                    this.file = event.target.files[0];
                },
                async extractText() {
                    if (!this.file) {
                        alert("Por favor, selecione um arquivo PDF primeiro.");
                        return;
                    }

                    const fileReader = new FileReader();
                    fileReader.onload = async (e) => {
                        const typedArray = new Uint8Array(e.target.result);
                        const pdf = await pdfjsLib.getDocument(typedArray).promise;
                        let text = '';

                        for (let i = 1; i <= pdf.numPages; i++) {
                            const page = await pdf.getPage(i);
                            const textContent = await page.getTextContent();
                            textContent.items.forEach(item => {
                                text += item.str + ' ';
                            });
                        }
                        this.completeString = text.slice(0, 6000);
                    };

                    fileReader.readAsArrayBuffer(this.file);
                }
            }
        }).mount('#app');
    </script>

</body>

</html>