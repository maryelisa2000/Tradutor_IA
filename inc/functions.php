<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DadosExternos
{

    static function DECS($termo)
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?words=' . trim(urlencode($termo)) . '',
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
            )
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $xml_extracted = simplexml_load_string($resp);
        $data = json_decode(json_encode($xml_extracted), true);
        if (isset($data['decsws_response']['tree'])) {
            ProcessaRegistros::DECS($data['decsws_response']);
        } elseif (isset($data['decsws_response'])) {
            if (count($data['decsws_response'])  > 0) {
                foreach ($data['decsws_response'] as $record) {
                    ProcessaRegistros::DECS($record);
                }
            }
        }
    }

    static function exatoDECS($termo)
    {

        // URL que você deseja acessar
        $url = 'https://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?bool=107%20' . trim(urlencode($termo)) . '';

        // Inicializa a sessão cURL
        $ch = curl_init();

        // Configurações da sessão cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9'
        ]);

        // Executa a sessão cURL e armazena a resposta
        $response = curl_exec($ch);

        // Verifica se houve algum erro
        if (curl_errno($ch)) {
            echo 'Erro: ' . curl_error($ch);
        } else {
            // Exibe a resposta
            $xml_extracted = simplexml_load_string($response);
            $data = json_decode(json_encode($xml_extracted), true);
            //echo "<pre>" . print_r($data, true) . "</pre>";
            if (isset($data['decsws_response']['tree'])) {
                ProcessaRegistros::DECS($data['decsws_response']);
            } elseif (isset($data['decsws_response'])) {
                if (count($data['decsws_response'])  > 0) {
                    foreach ($data['decsws_response'] as $record) {
                        ProcessaRegistros::DECS($record);
                    }
                }
            }
        }

        // Fecha a sessão cURL
        curl_close($ch);
    }

    static function exatoDECSComposto($termos)
    {
        echo '<div class="card-group">';
        foreach ($termos as $termo) {
            //var_dump($termo);
            // URL que você deseja acessar
            $url = 'https://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?bool=107%20' . urlencode(trim($termo)) . '';

            // Inicializa a sessão cURL
            $ch = curl_init();

            // Configurações da sessão cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9'
            ]);

            // Executa a sessão cURL e armazena a resposta
            $response = curl_exec($ch);

            // Verifica se houve algum erro
            if (curl_errno($ch)) {
                echo 'Erro: ' . curl_error($ch);
            } else {
                // Exibe a resposta
                $xml_extracted = simplexml_load_string($response);
                $data = json_decode(json_encode($xml_extracted), true);
                //echo "<pre>" . print_r($data, true) . "</pre>";
                if (isset($data['decsws_response']['tree'])) {
                    //ProcessaRegistros::DECSComposto($data['decsws_response']);

                    echo '<div class="card mb-3">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $data['decsws_response']['tree']['self']['term_list']['term'] . '</h5>';
                    echo '<p class="card-text"><b>Definição:</b> ' . $data['decsws_response']['record_list']['record']['definition']['occ']['@attributes']['n'] . '<br/>';

                    //echo "<pre>" . print_r($record['record_list']['record']['synonym_list'], true) . "</pre>";
                    echo '<b>Consulta expandida:</b> ' . implode(' OR ', $data['decsws_response']['record_list']['record']['descriptor_list']['descriptor']) . '<br/>';
                    if (count($data['decsws_response']['record_list']['record']['synonym_list']) > 0) {
                        if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                            $sinonimoArray[] = '(' . implode(' OR ', $data['decsws_response']['record_list']['record']['synonym_list']['synonym']) . ')';
                        } else {
                            if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                                $sinonimoArray[] = '(' . implode(' OR ', $data['decsws_response']['record_list']['record']['synonym_list']['synonym']) . ')';
                            }
                        }
                    }
                    if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                        echo '<b>Consulta por sinônimos:</b> ' . implode(' OR ', $sinonimoArray) . '</p>';
                    }


                    $consultaArray[] = '(' . implode(' OR ', $data['decsws_response']['record_list']['record']['descriptor_list']['descriptor']) . ')';

                    echo '</div>';
                    echo '</div>';
                } elseif (isset($data['decsws_response'])) {
                    if (count($data['decsws_response'])  > 0) {
                        foreach ($data['decsws_response'] as $record) {
                            ProcessaRegistros::DECSComposto($record);
                        }
                    }
                } else {
                    echo "A pesquisa não retornou nenhum dado";
                }
            }

            // Fecha a sessão cURL
            curl_close($ch);
        }

        echo "</div>";




        //echo "<pre>" . print_r($sinonimo_array, true) . "</pre>";

        echo '<p class="card-text"></p>';
        echo '<p class="card-text"><small class="text-body-secondary">Pesquisar nas seguintes fontes por idioma: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' AND ', $consultaArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' AND ', $consultaArray) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' AND ', $consultaArray) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . implode('&quot; AND &quot;', $consultaArray) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' AND ', $consultaArray) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' AND ', $consultaArray) . '" target="_blank">Zenodo</a>

        </small></br>';
        if (count($data['decsws_response']['record_list']['record']['synonym_list']) > 0) {
            if (is_array($data['decsws_response']['record_list']['record']['synonym_list']['synonym'])) {
                echo '<small class="text-body-secondary">Pesquisar nas seguintes fontes por sinônimo: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' AND ', $sinonimoArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Scopus</a> 
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=&quot;' . implode('&quot; AND &quot;', $sinonimoArray) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Zenodo</a>

                </small></p>';
            } else {
                if (isset($sinonimoArray)) {
                    echo '<small class="text-body-secondary">Pesquisar nas seguintes fontes por sinônimo: 
                    <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' AND ', $sinonimoArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                    <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Pubmed</a> 
                    <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Scopus</a>
                    <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=&quot;' . implode('&quot; AND &quot;', $sinonimoArray) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                    <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Mendeley Data</a>
                    <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' AND ', $sinonimoArray) . '" target="_blank">Zenodo</a>
    
                    </small></p>';
                }
            }
        }
    }

    static function Wikipedia($termo)
    {
        $url = "https://pt.wikipedia.org/w/api.php?action=query&format=json&prop=langlinks&lllimit=500&titles=" . trim(urlencode(strtolower($termo))) . "";
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
            'http' => [
                'ignore_errors' => true,
            ]
        ]);
        $resp = file_get_contents($url);
        $data = json_decode($resp, true);
        ProcessaRegistros::Wikipedia($data);
        //echo "<pre>" . print_r($data, true) . "</pre>";
    }

    static function WikipediaComposto($termos)
    {
        echo '<div class="card-group">';
        foreach ($termos as $termo) {
            $url = "https://pt.wikipedia.org/w/api.php?action=query&format=json&prop=langlinks&lllimit=500&titles=" . trim(urlencode(strtolower($termo))) . "";
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                'http' => [
                    'ignore_errors' => true,
                ]
            ]);
            $resp = file_get_contents($url);
            $data = json_decode($resp, true);
            foreach ($data['query']['pages'] as $page) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title"><a href="https://pt.wikipedia.org/?curid=' . $page['pageid'] . '">' . $page['title'] . '</a></h5>';
                $sinonimoArray = [];
                foreach ($page['langlinks'] as $langlink) {
                    $sinonimoArray[] = $langlink['*'];
                }
                echo '<p class="card-text"><b>Consulta por idioma:</b> ' . implode(' OR ', $sinonimoArray) . '</p>';
                $sinonimosArray[] =  '(' . implode(' OR ', $sinonimoArray) . ')';

                echo '</div>';
                echo '</div>';
            }
            echo '</div">';
        }

        echo '</div">';


        echo '</div><div><p><small class="text-body-secondary">Pesquisar nas seguintes fontes por idioma: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimosArray) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=&search_form_submit=&q=' . implode(' OR ', $sinonimosArray) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimosArray) . '" target="_blank">Zenodo</a>


        </small></p></div>';
        //echo "<pre>" . print_r($data, true) . "</pre>";
    }

    static function AGROVOC($termo)
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://agrovoc.fao.org/agrovoc/rest/v1/search/?lang=pt-BR&query=' . $termo . '',
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
            )
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $data = json_decode($resp, true);
        foreach ($data['results'] as $result) {
            //print("<pre>" . print_r($data['results'][0], true) . "</pre>");
            $uri = $result['uri'];
            // Get cURL resource
            $ch = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array(
                $ch,
                array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://agrovoc.fao.org/agrovoc/rest/v1/data/?format=application/json&uri=' . $uri . '',
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
                )
            );
            // Send the request & save response to $resp
            $resp_ch = curl_exec($ch);
            $data_ch = json_decode($resp_ch, true);
            if (isset($data_ch['graph'][3])) {
                ProcessaRegistros::AGROVOC($data_ch['graph'][3], $termo);
            }
        }
    }
}

class ProcessaRegistros
{
    static function DECS($record)
    {
        $sinonimo_array = [];
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $record['tree']['self']['term_list']['term'] . '</h5>';
        echo '<p class="card-text"><b>Definição:</b> ' . $record['record_list']['record']['definition']['occ']['@attributes']['n'] . '<br/>';
        echo '<b>Consulta expandida:</b> ' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '<br/>';
        //echo "<pre>" . print_r($record['record_list']['record']['synonym_list'], true) . "</pre>";
        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                $sinonimo_array = $record['record_list']['record']['synonym_list']['synonym'];
            } else {
                $sinonimo_array[] = $record['record_list']['record']['synonym_list']['synonym'];
            }
        }

        $sinonimo_array[] = $record['tree']['self']['term_list']['term'];
        //echo "<pre>" . print_r($sinonimo_array, true) . "</pre>";
        echo '<b>Consulta por sinônimos:</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
        echo '<p class="card-text"></p>';
        echo '<p class="card-text"><small class="text-body-secondary">Pesquisar nas seguintes fontes por idioma: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . implode('&quot; OR &quot;', $record['record_list']['record']['descriptor_list']['descriptor']) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Zenodo</a>

        </small></br>';

        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                echo '<small class="text-body-secondary">Pesquisar nas seguintes fontes por sinônimo: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=&quot;' . implode('&quot; OR &quot;', $sinonimo_array) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            } else {
                echo '<small class="text-body-secondary">Pesquisar nas seguintes fontes por sinônimo: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a>
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=&quot;' . implode('&quot; OR &quot;', $sinonimo_array) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            }
        }
        echo '</div>';
        echo '</div>';
        //echo "<pre>" . print_r($record, true) . "</pre>";
    }

    static function DECSComposto($record)
    {
        $sinonimo_array = [];
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $record['tree']['self']['term_list']['term'] . '</h5>';
        echo '<p class="card-text"><b>Definição:</b> ' . $record['record_list']['record']['definition']['occ']['@attributes']['n'] . '<br/>';
        echo '<b>Consulta expandida:</b> ' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '<br/>';
        //echo "<pre>" . print_r($record['record_list']['record']['synonym_list'], true) . "</pre>";
        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                $sinonimo_array = $record['record_list']['record']['synonym_list']['synonym'];
            } else {
                $sinonimo_array[] = $record['record_list']['record']['synonym_list']['synonym'];
            }
        }

        $sinonimo_array[] = $record['tree']['self']['term_list']['term'];
        //echo "<pre>" . print_r($sinonimo_array, true) . "</pre>";
        echo '<b>Consulta por sinônimos:</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
        echo '<p class="card-text"></p>';
        echo '<p class="card-text"><small class="text-body-secondary">Pesquisar nas seguintes fontes por idioma: 
        <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
        <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Pubmed</a> 
        <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Scopus</a> 
        <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&q=&quot;' . implode('&quot; OR &quot;', $record['record_list']['record']['descriptor_list']['descriptor']) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
        <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Mendeley Data</a>
        <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $record['record_list']['record']['descriptor_list']['descriptor']) . '" target="_blank">Zenodo</a>

        </small></br>';

        if (count($record['record_list']['record']['synonym_list']) > 0) {
            if (is_array($record['record_list']['record']['synonym_list']['synonym'])) {
                echo '<small class="text-body-secondary">Pesquisar nas seguintes fontes por sinônimo: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=&quot;' . implode('&quot; OR &quot;', $sinonimo_array) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            } else {
                echo '<small class="text-body-secondary">Pesquisar nas seguintes fontes por sinônimo: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a>
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=mh&search_form_submit=&q=&quot;' . implode('&quot; OR &quot;', $sinonimo_array) . '&quot;" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

                </small></p>';
            }
        }
        echo '</div>';
        echo '</div>';
        //echo "<pre>" . print_r($record, true) . "</pre>";
    }

    static function Wikipedia($record)
    {

        foreach ($record['query']['pages'] as $page) {
            if (isset($page['pageid'])) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title"><a href="https://pt.wikipedia.org/?curid=' . $page['pageid'] . '">' . $page['title'] . '</a></h5>';
                $sinonimo_array = [];
                foreach ($page['langlinks'] as $langlink) {
                    $sinonimo_array[] = $langlink['*'];
                }
                echo '<p class="card-text"><b>Consulta por idioma:</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
                echo '<p><small class="text-body-secondary">Pesquisar nas seguintes fontes por idioma: 
                <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=' . implode(' OR ', $sinonimo_array) . '&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
                <a class="btn btn-primary btn-sm" href="https://pubmed.ncbi.nlm.nih.gov/?term=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Pubmed</a> 
                <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
                <a class="btn btn-primary btn-sm" href="https://pesquisa.bvsalud.org/portal/?output=&lang=pt&from=&sort=&format=&count=&fb=&page=1&skfp=&index=&search_form_submit=&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">BVS (Biblioteca Virtual em Saúde)</a> 
                <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
                <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>
    
    
                </small></p>';

                echo '</div>';
                echo '</div>';
            } else {
                echo 'Sem resultados';
            }
        }
    }

    static function AGROVOC($record, $termo)
    {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title"><a href="' . $record['uri'] . '">' . $termo . '</a></h5>';
        $sinonimo_array = [];
        foreach ($record['prefLabel'] as $prefLabel) {
            $sinonimo_array[] = $prefLabel['value'];
        }
        echo '<p class="card-text"><b>Consulta por idioma:</b> ' . implode(' OR ', $sinonimo_array) . '</p>';
        echo '<p><small class="text-body-secondary">Pesquisar nas seguintes fontes por idioma: 
            <a class="btn btn-primary btn-sm" href="https://search.scielo.org/?q=(' . implode(') OR (', $sinonimo_array) . ')&lang=pt&filter%5Bin%5D%5B%5D=scl" target="_blank">Scielo</a> 
            <a class="btn btn-primary btn-sm" href="https://www.scopus.com/results/results.uri?sort=plf-f&src=s&sot=a&sdt=a&sl=51&origin=searchadvanced&limit=10&s=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Scopus</a> 
            <a class="btn btn-primary btn-sm" href="https://data.mendeley.com/research-data/?search=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Mendeley Data</a>
            <a class="btn btn-primary btn-sm" href="https://zenodo.org/search?l=list&p=1&s=10&sort=bestmatch&q=' . implode(' OR ', $sinonimo_array) . '" target="_blank">Zenodo</a>

            </small></p>';

        echo '</div></div>';
    }
}

class Parser
{
    static function parserQueryExists($request)
    {
        if (!empty($request['search'])) {
            return true;
        } else {
            return false;
        }
    }

    static function parserQuery($request)
    {
        $terms = explode("AND", $request['search']);
        return $terms;
    }
}