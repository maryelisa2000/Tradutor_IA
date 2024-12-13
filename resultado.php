<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.php';
require('inc/config.php');

$resultParserExists = Parser::parserQueryExists($_REQUEST);


use ModelflowAi\Ollama\Ollama;

// Create a client instance
$client = Ollama::client();

// Use the client
$chat = $client->chat();
// $completion = $client->completion();
// $embeddings = $client->embeddings();

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultado de busca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php include_once('inc/header.php'); ?>
    <div class="container">
        <main>

            <?php if ($resultParserExists): ?>

            <div class="alert alert-info" role="alert">
                Você pesquisou por: <?php echo $_REQUEST['search'] ?>
            </div>

            <?php //Parser::parserQuery($_REQUEST); 
                ?>

            <?php if (count(Parser::parserQuery($_REQUEST)) == 1): ?>

            <?php if (isset($_REQUEST['OpenAI'])): ?>

            <h3>Inteligência Artificial (IA) - ChatGPT</h3>
            <div class="card mb-3">
                <div class="card-body">

                    <p><b>Definição:</b>
                        <?php

                                    $yourApiKey = $OAApiKey;
                                    $client = OpenAI::client($yourApiKey);

                                    $result = $client->chat()->create([
                                        'model' => 'gpt-4o-mini',
                                        'messages' => [
                                            ['role' => 'user', 'content' => 'Defina de maneira extensiva:' . $_REQUEST['search'] . ''],
                                        ],
                                    ]);

                                    echo $result->choices[0]->message->content;

                                    ?>
                    </p>
                    <h5>Inteligência Artificial (IA) - ChatGPT - Traduções</h5>

                    <?php

                                $result2 = $client->chat()->create([
                                    'model' => 'gpt-4o-mini',
                                    'messages' => [
                                        ['role' => 'user', 'content' => 'Liste as traduções em todos os idiomas que você conhece do termo:' . $_REQUEST['search'] . '. Não é preciso incluir uma definição'],
                                    ],
                                ]);

                                echo $result2->choices[0]->message->content;

                                ?>
                    <br /><br />
                    <?php

                                $result3 = $client->chat()->create([
                                    'model' => 'gpt-4o-mini',
                                    'messages' => [
                                        ['role' => 'user', 'content' => 'Liste as traduções em todos os idiomas que você conhece do termo ' . $_REQUEST['search'] . ' em uma lista separada por |, sem incluir o idioma, somente a lista dos resultados. Não é preciso incluir uma definição. Responda somente a lista'],
                                    ],
                                ]);

                                $traducoesOpenAI = explode('|', $result3->choices[0]->message->content);
                                echo 'Pesquisar nas seguintes fontes por idioma: ';
                                echo '<a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(" OR ", $traducoesOpenAI) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a>';
                                echo '<a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $traducoesOpenAI) . '" target="_blank">Pubmed</a>';
                                echo '<a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $traducoesOpenAI) . '" target="_blank">Scopus</a>';
                                echo '<a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . implode('&quot; OR &quot;', $traducoesOpenAI) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';

                                ?>
                </div>
            </div>

            <?php endif; ?>

            <?php if (isset($_REQUEST['IA'])): ?>

            <h3>Inteligência Artificial (IA) - Llama 3.1</h3>
            <div class="card mb-3">
                <div class="card-body">


                    <p><b>Definição:</b>
                        <?php

                                    // Example usage of chat
                                    $chatResponse = $chat->create([
                                        'model' => 'llama3.1',
                                        'messages' => [['role' => 'user', 'content' => 'Defina de maneira extensiva:' . $_REQUEST['search'] . '']],
                                    ]);
                                    echo $chatResponse->message->content . \PHP_EOL;
                                    ?>
                    </p>
                    <h5>Inteligência Artificial (IA) - Llama 3.1 - Traduções</h5>

                    <?php
                                // Example usage of chat
                                $chatResponse2 = $chat->create([
                                    'model' => 'llama3.1',
                                    'messages' => [['role' => 'user', 'content' => 'Liste as traduções em todos os idiomas que você conhece do termo:' . $_REQUEST['search'] . '. Não é preciso incluir uma definição']],
                                ]);
                                echo $chatResponse2->message->content . \PHP_EOL;

                                ?>
                    <br /><br />
                    <?php
                                // Example usage of chat
                                $chatResponse3 = $chat->create([
                                    'model' => 'llama3.1',
                                    'messages' => [['role' => 'user', 'content' => 'Liste as traduções em todos os idiomas que você conhece do termo ' . $_REQUEST['search'] . ' em uma lista separada por |, sem incluir o idioma, somente a lista dos resultados. Não é preciso incluir uma definição. Responda somente a lista']],
                                ]);
                                $traducoes = explode('|', $chatResponse3->message->content);
                                echo 'Pesquisar nas seguintes fontes por idioma: ';
                                echo '<a href="https://search.scielo.org/?q=' . implode(" OR ", $traducoes) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> - ';
                                echo '<a href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $traducoes) . '" target="_blank">Pubmed</a> - ';
                                echo '<a href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $traducoes) . '" target="_blank">Scopus</a> - ';
                                echo '<a href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . implode('&quot; OR &quot;', $traducoes) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';

                                ?>
                </div>
            </div>

            <?php endif; ?>

            <?php if ($_REQUEST['area'] == 'Medicina'): ?>
            <h3>DECS Exato</h3>
            <?php DadosExternos::exatoDECS($_REQUEST['search']); ?>

            <h3>DECS Busca</h3>
            <?php DadosExternos::DECS($_REQUEST['search']); ?>
            <?php endif; ?>

            <?php if ($_REQUEST['area'] == 'Agricultura'): ?>
            <h3>AGROVOC</h3>
            <?php DadosExternos::AGROVOC($_REQUEST['search']); ?>
            <?php endif; ?>

            <h3>Wikipédia</h3>
            <?php DadosExternos::Wikipedia($_REQUEST['search']); ?>
            <?php else: ?>

            <h3>DECS Exato</h3>
            <?php if ($_REQUEST['area'] == 'Medicina') {
                        DadosExternos::exatoDECSComposto(Parser::parserQuery($_REQUEST));
                    }
                    ?>

            <h3>Wikipédia</h3>
            <?php DadosExternos::WikipediaComposto(Parser::parserQuery($_REQUEST));
                    ?>
            <?php endif; ?>

            <?php else: ?>

            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Não foi informado nenhum termo de busca</h4>
                <p>Você pode refazer sua busca</p>
                <hr>
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
                    </form>
                </div>
            </div>



            <?php endif; ?>

        </main>

        <?php include_once('inc/footer.php'); ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>