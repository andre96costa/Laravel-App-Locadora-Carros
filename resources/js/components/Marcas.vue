<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Buscar Marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="idInput" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da Marca.">
                                    <input type="number" class="form-control" id="idInput" aria-describedby="idHelp">
                                </input-container-component>  
                            </div>
                            <div class="col mb-3">
                                <input-container-component  titulo="Marca" id="nomeMarcaInput" id-help="nomeMarcaInputHelp" texto-ajuda="Opcional. Informe o Nome da Marca.">
                                    <input type="text" class="form-control" id="nomeMarcaInput" aria-describedby="nomeMarcaInputHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#marcaModal">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
        <modal-component id="marcaModal" title="Adicionar Nova Marca" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacoesDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacoesDetalhes" titulo="Errou ao cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="Nome da Marca" id="nomeInput">
                    <input type="text" class="form-control" id="nomeInput" placeholder="Marca" v-model="nomeMarca">
                </input-container-component>  
                <input-container-component titulo="Logo Marca" id="logoInput" texto-ajuda="Selecione uma imagem no formato PNG">
                    <br>
                    <input type="file" class="form-control-file" id="logoInput" v-on:change="carregarImagem($event)">
                </input-container-component> 
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="salvar()">Adicionar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.carregarLista();
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacoesDetalhes: {},
                marcas: [],
            }
        },
        methods: {
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                let formData = new FormData();
                formData.append('nome', this.nomeMarca);
                formData.append('imagem', this.arquivoImagem[0]);
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.tokenApi,
                    },
                }
                axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transacaoStatus = 'adicionado';
                    this.transacoesDetalhes = {
                        mensagem: 'ID do registro '+ response.data.id 
                    };
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro';
                    this.transacoesDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors,
                    };
                });
            },
            carregarLista() {
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.tokenApi,
                    },
                }
                axios.get(this.urlBase, config)
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
        },
        computed: {
            tokenApi() {
                let token = document.cookie.split(';')
                .find(row => row.startsWith('token='))
                .split("=");
                return "Bearer "+token[1];
            },
        },
    };
</script>