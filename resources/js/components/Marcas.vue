<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Buscar Marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="idInput" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da Marca.">
                                    <input v-model="busca.id" type="number" class="form-control" id="idInput" aria-describedby="idHelp">
                                </input-container-component>  
                            </div>
                            <div class="col mb-3">
                                <input-container-component  titulo="Marca" id="nomeMarcaInput" id-help="nomeMarcaInputHelp" texto-ajuda="Opcional. Informe o Nome da Marca.">
                                    <input v-model="busca.nome" type="text" class="form-control" id="nomeMarcaInput" aria-describedby="nomeMarcaInputHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" v-on:click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component 
                            v-bind:dados="marcas.data" 
                            v-bind:visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#marcaModalVisualizar'}"
                            v-bind:atualizar="true"
                            v-bind:remover="{visivel: true, dataToggle: 'modal', dataTarget: '#marcaModalRemover'}"
                            v-bind:titulos="{
                                id: {titulo: 'ID', tipo: 'text'},
                                nome: {titulo: 'Nome', tipo: 'text'},
                                imagem: {titulo: 'Imagem', tipo: 'imagen'},
                            }"
                        ></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div></div>
                        <paginate-component>
                            <li v-for="(l, key) in marcas.links" :key="key" v-bind:class="l.active ? 'page-item active' : 'page-item'">
                                <a class="page-link" href="#" v-html="l.label" v-on:click="paginacao(l)" ></a>
                            </li>
                        </paginate-component>
                        <button type="button" class="btn btn-primary btn-sm float-right align-self-end" style="height: 28px;" data-bs-toggle="modal" data-bs-target="#marcaModal">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- MODAL INCLUSAO DE MARCA -->
        <modal-component id="marcaModal" title="Adicionar Nova Marca" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso" v-if="$store.state.transacao.status == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao cadastrar a marca" v-if="$store.state.transacao.status == 'erro'"></alert-component>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="salvar()">Adicionar</button>
            </template>
        </modal-component>

        <!-- MODAL DE VISUALIZAÇÃO DE MARCA -->
        <modal-component id="marcaModalVisualizar" title="Visualizar Nova Marca" >
            <template v-slot:alertas>
            </template>
            <template v-slot:conteudo>
                <input-container titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                </input-container>
                <input-container titulo="Nome">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.nome" disabled>
                </input-container>
                <input-container titulo="Logo">
                    <img v-bind:src="'/storage/'+$store.state.item.imagem" :alt="'imagem-'+$store.state.item.nome" v-if="$store.state.item.imagem">
                </input-container>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </template>
        </modal-component>

        <!-- MODAL DE REMOÇÃO DE MARCAS -->
        <modal-component id="marcaModalRemover" title="Remover Marca" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Remoção de registro" v-if="$store.state.transacao.status == 'removido'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao remover o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="transacaoStatus != 'removido'">
                <span>Tem certeza que deseja remover está marca?</span>
                <input-container titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                </input-container>
                <input-container titulo="Nome">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.nome" disabled>
                </input-container>
            </template>
            <template v-slot:rodape>
                <div class="d-flex justify-content-between" style="width: 100%;">
                    <button type="button" class="btn btn-danger" v-on:click="remover()" v-if="transacaoStatus != 'removido'">Remover</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </template>
        </modal-component>
    </div>

</template>

<script>
import InputContainer from './InputContainer.vue';
    export default {
  components: { InputContainer },
        mounted() {
            this.carregarLista();
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacoesDetalhes: {},
                marcas: {data: []},
                busca: { id: '', nome: ''},
            }
        },
        methods: {
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
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
                    this.$store.state.transacao.mensagem = 'ID do registro '+ response.data.id;
                    this.$store.state.transacao.status = 'adicionado';
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.dados = errors.response.data.errors;
                    this.$store.state.transacao.status = 'erro';
                });
            },
            carregarLista() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.tokenApi,
                    },
                }
                axios.get(url, config)
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            paginacao(l) {
                if (l.url) {
                    this.urlPaginacao = l.url.split('?')[1];
                    this.carregarLista();
                }
            },
            pesquisar() {
                let filtro = '';
                this.urlFiltro = '';
                for (const key in this.busca) {
                    if (this.busca[key]) {
                        if (filtro != '') {
                            filtro += ';'
                        }
                        filtro += key+':like:%'+this.busca[key]+'%';
                    }
                }
                if (filtro != '') {
                    this.urlPaginacao = 'page=1';
                    this.urlFiltro = '&filtro='+filtro;
                }
                this.carregarLista();
            },
            remover() {
                let url = this.urlBase+'/'+this.$store.state.item.id;
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.tokenApi,
                    }
                }
                axios.delete(url, config)
                .then((response) => {
                    this.$store.state.transacao.mensagem = 'O registro foi removido com sucesso!';
                    this.$store.state.transacao.status = 'removido';
                    this.carregarLista();
                })
                .catch((error) => {
                    this.$store.state.transacao.mensagem = 'Houve um erro ao tentar remover o registro!';
                    this.$store.state.transacao.status = 'erro';
                });
            },
            clearStore() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                this.$store.state.transacao.dados = '';
            }
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