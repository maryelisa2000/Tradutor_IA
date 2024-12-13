<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>
    <title>Expansor de buscas</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="description" content="Expansor de buscas" />
    <meta name="keywords" content="Produção acadêmica, lattes, ORCID" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
    .c-who {
        align-items: center;
        flex-direction: column;
        justify-content: flex-start;
        width: 200px;
    }

    .c-who-text {
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .c-who-text,
    .c-who-name,
    .c-who-title {
        text-align: center;
    }

    .c-who-photo {
        height: 100px;
        width: 100px;
    }

    .c-who-name {
        color: #000;
        font-size: 1.2rem;
    }

    .c-who-title {
        color: var(--c-mn-20);
    }

    .c-whomini {
        display: grid;
        grid-template-areas: "a b";
        margin-block: 0.5rem;
    }

    .c-whomini-photo {
        grid-area: a;
        height: 50px;
        margin: 0 1rem 0 0;
        width: 50px;
    }

    .c-whomini-text {
        align-items: flex-start;
        display: flex;
        flex-direction: column;
        grid-area: b;
        justify-content: center;
    }

    .c-whomini-name {
        color: #000;
    }

    .c-who-s {
        height: 200px;
        margin: 0rem auto;
        position: relative;
        width: 200px;
    }

    .c-who-s-badge {
        position: absolute;
        right: 5%;
        top: 70%;
        width: 50px;
        z-index: 999;
    }

    .c-who-s-pic {
        height: 200px;
        -webkit-mask-image: url("data:image/svg+xml;utf8, <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 200 200'><circle cx='100' cy='100' r='100'/></svg>");
        mask-image: url("data:image/svg+xml;utf8, <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 200 200'><circle cx='100' cy='100' r='100'/></svg>");
        -webkit-mask-position: center;
        mask-position: start;
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
        -webkit-mask-size: 100% 100%;
        mask-size: 100% 100%;
        width: 200px;
    }

    .p-about-team {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-block: 2rem;
        width: 100%;
    }
    </style>

</head>

<body>
    <?php include_once('inc/header.php');
    ?>
    <main class="p-about container">
        <section class="p-about-section1">
            <h1 class="t t-h2 u-my-10">O Expansor de Buscas visa diminuir a barreira linguística na literatura
                científica </h1>
            <p class="p-about-text">O expansor de Buscas é uma ferramenta desenvolvida pelo Laboratório de dados,
                métricas institucionais e reprodutibilidade científica (Datalab) da UFRGS, visando resolver um dos
                principais limitadores quanto à recuperação de informação científica na contemporaneidade: a barreira
                linguística. A partir da utilização de diferentes ferramentas para a tradução de termos da área médica -
                especialmente o tesauro multilíngue Medical Subject Headings (MeSH), wikipedia e Modelos de linguagem de
                grande escala (especificamente o Llama 3 desenvolvido pela Meta) - esta ferramenta objetiva traduzir os
                termos inseridos na barra de pesquisa e expandir os resultados de busca incluindo as traduções nas
                línguas que o usuário quiser. No momento é uma ferramenta aplicada para a área das ciências médicas, ao
                usar tesauros e serviços desta área, enquanto serve também como protótipo que poderá ser adaptado para
                outras bases de dados, serviços de busca e áreas do conhecimento. </p>
            <h3 class="t t-h3 u-my-10">É livre ! É código aberto ! </h3><a
                href="https://github.com/trmurakami/tradutor_ia" target="blank">
                <p class="t t-a">Visite o nosso repositório Github</p><svg title="Github"
                    alt="Acesse o repositório Github" class="p-about-ico" xmlns="https://www.w3.org/2000/svg"
                    viewBox="0 0 64 64" width="64px" height="64px">
                    <path
                        d="M32 6C17.641 6 6 17.641 6 32c0 12.277 8.512 22.56 19.955 25.286-.592-.141-1.179-.299-1.755-.479V50.85c0 0-.975.325-2.275.325-3.637 0-5.148-3.245-5.525-4.875-.229-.993-.827-1.934-1.469-2.509-.767-.684-1.126-.686-1.131-.92-.01-.491.658-.471.975-.471 1.625 0 2.857 1.729 3.429 2.623 1.417 2.207 2.938 2.577 3.721 2.577.975 0 1.817-.146 2.397-.426.268-1.888 1.108-3.57 2.478-4.774-6.097-1.219-10.4-4.716-10.4-10.4 0-2.928 1.175-5.619 3.133-7.792C19.333 23.641 19 22.494 19 20.625c0-1.235.086-2.751.65-4.225 0 0 3.708.026 7.205 3.338C28.469 19.268 30.196 19 32 19s3.531.268 5.145.738c3.497-3.312 7.205-3.338 7.205-3.338.567 1.474.65 2.99.65 4.225 0 2.015-.268 3.19-.432 3.697C46.466 26.475 47.6 29.124 47.6 32c0 5.684-4.303 9.181-10.4 10.4 1.628 1.43 2.6 3.513 2.6 5.85v8.557c-.576.181-1.162.338-1.755.479C49.488 54.56 58 44.277 58 32 58 17.641 46.359 6 32 6zM33.813 57.93C33.214 57.972 32.61 58 32 58 32.61 58 33.213 57.971 33.813 57.93zM37.786 57.346c-1.164.265-2.357.451-3.575.554C35.429 57.797 36.622 57.61 37.786 57.346zM32 58c-.61 0-1.214-.028-1.813-.07C30.787 57.971 31.39 58 32 58zM29.788 57.9c-1.217-.103-2.411-.289-3.574-.554C27.378 57.61 28.571 57.797 29.788 57.9z" />
                </svg>
            </a>
        </section>
        <section class="p-about-section2">
            <h3 class="t t-h3 u-my-10">Apoio </h3>
            <p class="p-about-text">Esta ferramenta teve financiamento CNPq</p>
            <h3 id="créditos" class="t t-h3 u-my-10">Realização</h3>
            <p class="t t-md">Universidade Federal do Rio Grande do Sul</p>
            <p class="t t-md t-gray">Laboratório de dados,
                métricas institucionais e reprodutibilidade científica - Datalab da UFRGS</p><a
                href="https://unifesp.br/" target="_blank" title="Visite o site da Unifesp"><img class="p-about-logos"
                    src="https://www.ufrgs.br/datalab/wp-content/themes/datalab/static/img/logos/logo.svg"
                    alt="Logo da Unifesp"></a></div>
            <h3 id="créditos" class="t t-h3 u-my-10">Equipe</h3>
            <div class="p-about-team">


                <a href='http://lattes.cnpq.br/4635807083312321' class='c-who' target='blank' />
                <img class='c-who-photo u-grayscale'
                    src="http://servicosweb.cnpq.br/wspessoa/servletrecuperafoto?tipo=1&id=K4509021Y6" />
                <div class='c-who-text'>
                    <b>Fabiano Couto Corrêa da Silva</b>
                </div>
                </a>

                <a href='http://lattes.cnpq.br/5403684310321604' class='c-who' target='blank' />
                <img class='c-who-photo u-grayscale' src="https://avatars.githubusercontent.com/u/170115359?v=4" />
                <div class='c-who-text'>
                    <b>Maria Elisabeth Vasconcellos Monteiro</b>
                </div>
                </a>

                <a href='http://lattes.cnpq.br/0306160176168674' class='c-who' target='blank' />
                <img class='c-who-photo u-grayscale' src="https://avatars.githubusercontent.com/u/499115?v=4" />
                <div class='c-who-text'>
                    <b>Tiago Rodrigo Marçal Murakami</b>
                </div>
                </a>



            </div>
        </section>
    </main><?php include('inc/footer.php');
            ?>
</body>

</html>