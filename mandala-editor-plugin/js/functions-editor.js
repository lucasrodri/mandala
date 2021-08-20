var testDataTeste = [
{id: 1, name: 'My Organization', url: 'http://teste.comdjkfhlskjfhldskjfhlsdkjfhsdlkjfhsdlkjfhsldkj', parent: 0},
{id: 2, name: 'CEO Office', url: 'http://teste.com', parent: 1},
{id: 3, name: 'Division 1', url: 'http://teste.com', parent: 1},
{id: 4, name: 'Division 2', url: 'http://teste.com', parent: 1},
{id: 6, name: 'Division 3', url: 'http://teste.com', parent: 1},
{id: 7, name: 'Division 4', url: 'http://teste.com', parent: 1},
{id: 8, name: 'Division 5', url: 'http://teste.com', parent: 1},
{id: 5, name: 'Sub Division', url: 'http://teste.com', parent: 3},
];

//console.log({testData});

/* $(function(){
$('#orgChart').replaceWith("<p> TESTE </p>");
}); */
//console.log("mal feito feito");

// Precisa do $function pq está em outra página
$(function(){
    // função para criar um chart novo, recebe a variável de dados
    
    function startChart(testData){
        org_chart = $('#orgChart').orgChart({
            data: testData,
            showControls: true,
            allowEdit: true
        });
        return org_chart;
    }

    // adiciona a função ao botão de mostrar 
    $('#btn-mostrar').click(function(){
        //console.log(JSON.stringify(org_chart.getData()));
        alert(JSON.stringify(org_chart.getData()));
    });

    // Funcção para carregar novos dados
    $('#btn-carregar').click(function(){
        //crio novos dados diferentes
        var novo = [
            {id: 1, name: 'Azul', url: 'http://teste.comdjkfhlskjfhldskjfhlsdkjfhsdlkjfhsdlkjfhsldkj', parent: 0},
            {id: 2, name: 'Amarelo', url: 'http://teste.com', parent: 1},
            {id: 3, name: 'Verde', url: 'http://teste.com', parent: 1},
            {id: 4, name: 'Vermelho', url: 'http://teste.com', parent: 1},
            {id: 6, name: 'Cinza', url: 'http://teste.com', parent: 2},
            {id: 7, name: 'Roxo', url: 'http://teste.com', parent: 3},
            {id: 8, name: 'Rosa', url: 'http://teste.com', parent: 4},
            {id: 5, name: 'Preto', url: 'http://teste.com', parent: 1},
            ];

        //instancio novamente a org_chart com esses valores (uso a mesma variável para manter o funcionamento do btn-mostrar)
        org_chart = startChart(novo);
    });

    // Função para baixar o conteúdo do json
    $('#btn-download').click(function(){
        const a = document.createElement("a");
        a.href = URL.createObjectURL(new Blob([JSON.stringify(org_chart.getData())], {
            type: "text/plain"
        }));
        
        a.setAttribute("download", "data.json");
        document.body.appendChild(a);
        
        a.click();
        document.body.removeChild(a);
    });


    // Função para salvar o conteúdo do json
    $('#btn-save').click(function(){
        var json_content = JSON.stringify(org_chart.getData());
        //console.log("original")
        //console.log(json_content);
        
        //console.log("troca id")
        //salvava "id":1, mas preciso que seja "id":"1"
        json_content = json_content.replace(/"id":(\d+)/g,'"id":"$1"');
        //console.log(json_content);
        
        //console.log("troca conteudo")
        // mesma coisa para parent
        json_content = json_content.replace(/"parent":(\d+)/g,'"parent":"$1"');
        //console.log(json_content);

        /*
        var data = {
            'action': 'my_action',
            'whatever': 1234
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(ajaxurl, data, function(response) {
            alert('Got this from the server: ' + response);
        });
        */

        //https://codex.wordpress.org/AJAX_in_Plugins
        var data = {
            'action': 'salvar_txt_mandala',
            'json_content': json_content
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(ajaxurl, data, function(response) {
            alert('Arquivo salvo em: ' + response);
        });

    });
    
    //org_chart = startChart(testData);

    // Assim ele pega os dados do arquivo que ele mesmo criou
    //http://localhost\devsite-2021\wp-content\plugins\mandala-plugin//newfile.txt
    fetch('\\devsite-2021\\wp-content\\plugins\\mandala-plugin\\newfile.txt',{mode: 'no-cors'})
      .then((res) => res.text())
      .then((data) => {
        dataJS = JSON.parse(data);
       
        org_chart = startChart(dataJS);

        /*var elemento = $('<span class="org-chart">RCC</span>');
        $('#editor-1').find('h2').replaceWith(elemento);
        $('#editor-1').find('h3').remove();*/
    });
});