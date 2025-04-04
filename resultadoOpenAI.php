<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* Load libraries for PHP composer */
require(__DIR__ . '/vendor/autoload.php');
require('inc/config.php');

$yourApiKey = $OAApiKey;
$client = OpenAI::client($yourApiKey);


?>

<?php

ini_set("default_socket_timeout", 24000);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/config.php';


$yourApiKey = $OAApiKey;
$client = OpenAI::client($yourApiKey);
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
            <h3>Analisador de artigos usando Inteligência Artificial (ChatGPT)</h3>
            <div class="card mb-3">
                <div class="card-body">


                    <p><b>Resumo:</b>
                        <?php
                        $result = $client->chat()->create([
                            'model' => 'gpt-4o-mini',
                            'messages' => [
                                ['role' => 'user', 'content' => 'Responda em Português sobre o que é o texto?:  ' . $_REQUEST['userInput'] . ''],
                            ],
                        ]);

                        echo $result->choices[0]->message->content . \PHP_EOL;

                        ?>
                    </p>
                    <h5>Inteligência Artificial (IA) - ChatGPT - Palavras chave</h5>

                    <?php

                    $result2 = $client->chat()->create([
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'user', 'content' => 'Liste as 3 principais palavras chave. Responda em Português. Não é preciso incluir uma definição'],
                        ],
                    ]);

                    echo $result2->choices[0]->message->content . \PHP_EOL;

                    ?>
                    <br /><br />
                    <?php

                    $result3 = $client->chat()->create([
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'user', 'content' => 'Liste as 3 principais palavras chave em uma lista separada por |, sem incluir o idioma, somente a lista dos resultados. Não é preciso incluir uma definição. Responda somente a lista'],
                        ],
                    ]);

                    $traducoes = explode('|', $result3->choices[0]->message->content);
                    echo 'Pesquisar nas seguintes fontes por idioma: ';
                    echo '<a href="https://search.scielo.org/?q=' . implode(" OR ", $traducoes) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> - ';
                    echo '<a href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $traducoes) . '" target="_blank">Pubmed</a> - ';
                    echo '<a href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $traducoes) . '" target="_blank">Scopus</a> - ';
                    echo '<a href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . implode('&quot; OR &quot;', $traducoes) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a>';

                    ?>
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