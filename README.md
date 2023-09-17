## Tecnologias utilizadas e por quê;
Utilizado LUMEN para o backend, pois é um framework mais compacto que o Laravel (são da mesma equipe), por ser focado apenas para fazer API. 

Utilizado VUE.JS para o backend. Esse é meu primeiro projeto em VUE.JS, foquei em entender o máximo da estruturação, configuração e escalabilidade da aplicação. Tomei o teste como um desafio, por não ter experiência com esta tecnologia. Com isso, e também, por falta de tempo, não consegui focar no DESIGN. Apenas montei o necessário e o que foi solicitado.

Tratei o teste como um total cenário real (tirando a parte da falta de Design). Aplicando estratégias SOLID. Boa parte do quê foi desenvolvido pode ser reutilizado em N outra páginas e aplicado a regra de negócio específica para cada.

Utilizado MYSQL para banco de dados. 

## Estrutura de arquivos;
Criei esse repositório como monolito apenas para facilitar a vida do testador/avaliador. Essa estruturação não coincide com uma aplicação real.

## Como rodar;
Primeiramente, verifique se está sendo rodado o serviço MYSQL
Pelo terminal, dentro da pasta do projeto, apenas rode ~ 'start .\start.bat'.
Fique atento aos retornos dos Prompts de Comando. Alguns solicitarão algum input.

No projeto do VUE, o .env utiliza a rota do backend. Caso rode em uma fora do padrão, necessário ajustá-la.

## Observações:
### por favor, leia;

Tempo gasto em código: aproximadamente 14/15 horas.

Melhorias que tenho ciência de ser extremamente necessário; 
1. Design.
2. Melhorar validação na ponta do cliente.
3. Criar novos tipos de INPUT para validações específicas; "telefone", "documento".
4. Utilizar o FileSystem para o gerenciamento dos arquivos (possibilita integrações com ObjectStorage, como por exemplo o s3 da AWS e o BlobStorage da Azure).
5. Testes. Pensei em montar o projeto em base de TDD (Test-driven development), porém ficou apertado o tempo.
6. Paginações.
7. Cache.

Sinta-se livre para visitar minhas aplicações!
https://yoarajota.vercel.app
https://matchboxd.vercel.app
https://ioweasy.vercel.app