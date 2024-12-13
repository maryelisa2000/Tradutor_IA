<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datalab UFRGS - Expansor de buscas - Projeto de pesquisa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    .central-container {
        height: 40vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    </style>
</head>

<body>

    <?php include_once('inc/header.php'); ?>
    <div class="container">
        <main>
            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal text-body-emphasis">Expansor de buscas</h1>
                <p class="fs-5 text-body-secondary">Software desenvolvido pelo Laboratório de dados, métricas
                    institucionais
                    e reprodutibilidade científica - Datalab da UFRGS, com o objetivo de expandir termos de buscas para
                    ampliar os resultados obtidos em diversas bases de pesquisa</p>
                <div class="row justify-content-center central-container">
                    <form action="resultado.php" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar..." name="search">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                        <div class="row">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h4>Área principal de busca</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Medicina"
                                        value="Medicina" checked>
                                    <label class="form-check-label" for="Medicina">
                                        Medicina
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Agricultura"
                                        value="Agricultura">
                                    <label class="form-check-label" for="Agricultura">
                                        Agricultura
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="area" id="Outras" value="Outras">
                                    <label class="form-check-label" for="Outras">
                                        Outras áreas
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm"></div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h5>Usar Inteligência Artificial (ChatGPT)?
                                </h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="IASwitch"
                                        name="OpenAI">
                                    <label class="form-check-label" for="IASwitch">Sim</label>
                                </div>

                            </div>
                            <div class="col-sm"></div>
                        </div>
                        <!--
                        <div class="row mt-5">
                            <div class="col-sm"></div>
                            <div class="col-sm">
                                <h5>Usar Inteligência Artificial (Llama 3.1)? (Pode aumentar bastante o tempo de
                                    resposta
                                    necessário)
                                </h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="IASwitch"
                                        name="IA">
                                    <label class="form-check-label" for="IASwitch">Sim</label>
                                </div>

                            </div>
                            <div class="col-sm"></div>
                        </div>
                        -->
                    </form>
                </div>
            </div>
        </main>
        <?php include_once('inc/footer.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>