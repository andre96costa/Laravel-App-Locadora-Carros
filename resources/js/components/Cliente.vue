<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Buscar Cliente">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="idInput" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do cliente.">
                                    <input v-model="busca.id" type="number" class="form-control" id="idInput" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component  titulo="Nome" id="nomeInput" id-help="nomeInputHelp" texto-ajuda="Opcional. Informe o nome.">
                                    <input v-model="busca.nome" type="text" class="form-control" id="nomeInput" aria-describedby="nomeInputHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Lista dos Clientes">
                    <template v-slot:conteudo>
                        <table-component 
                            v-bind:dados="clientes.data" 
                            v-bind:visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#clienteModalVisualizar'}"
                            v-bind:atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#clienteModalAtualizar'}" 
                            v-bind:remover="{visivel: true, dataToggle: 'modal', dataTarget: '#clienteModalRemover'}"
                            v-bind:titulos="{
                                id: {titulo: 'ID', tipo: 'text', show: true},
                                nome: {titulo: 'Nome', tipo: 'text', show: true},
                            }"
                        ></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div></div>
                        <paginate-component>
                            <li v-for="(l, key) in clientes.links" :key="key" v-bind:class="l.active ? 'page-item active' : 'page-item'">
                                <a class="page-link" href="#" v-html="l.label" v-on:click="paginacao(l)" ></a>
                            </li>
                        </paginate-component>
                        <button type="button" class="btn btn-primary btn-sm float-right align-self-end" style="height: 28px;" data-bs-toggle="modal" data-bs-target="#clienteModal" @click="clearModalFields()">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- MODAL INCLUSAO DE CLIENTE -->
        <modal-component id="clienteModal" title="Adicionar Novo Cliente" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso" v-if="$store.state.transacao.status == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao cadastrar modelo" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="Cliente" id="nomeInput">
                    <input type="text" class="form-control" id="nomeInput" placeholder="Nome" v-model="nome">
                </input-container-component>  
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="salvar()" >Adicionar</button>
            </template>
        </modal-component>

        <!-- MODAL VISUALIZAR DADOS CLIENTE -->
        <modal-component id="clienteModalVisualizar" title="Dados do cliente" >
            <template v-slot:conteudo>
                <input-container-component titulo="ID" id="idInput">
                    <input type="number" class="form-control" id="idInput" placeholder="Id" :value="$store.state.item.id" disabled>
                </input-container-component> 
                <input-container-component titulo="Cliente" id="nomeInput">
                    <input type="text" class="form-control" id="nomeInput" placeholder="Nome" :value="$store.state.item.nome" disabled>
                </input-container-component> 
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
            </template>
        </modal-component>

        <!-- MODAL DE REMOÇÃO DE Cliente -->
        <modal-component id="clienteModalRemover" title="Remover Cliente" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Remoção de registro" v-if="$store.state.transacao.status == 'removido'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao remover o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'removido'">
                <span>Tem certeza que deseja remover está cliente?</span>
                <input-container titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                </input-container>
                <input-container titulo="Nome">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.nome" disabled>
                </input-container>
            </template>
            <template v-slot:rodape>
                <div class="d-flex justify-content-between" style="width: 100%;">
                    <button type="button" class="btn btn-danger" v-on:click="remover()" v-if="$store.state.transacao.status != 'removido'">Remover</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </template>
        </modal-component>

        <!-- MODAL DE ATUALIZACAO DO CLIENTE -->
        <modal-component id="clienteModalAtualizar" title="Atualizar Cliente" :data-modalIsOpen="isModalClosed = $store.state.isModalOpened">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização de registro" v-if="$store.state.transacao.status == 'atualizar'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao atualizar o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                </input-container>
                <input-container titulo="Nome">
                        <input type="text" class="form-control" :value="$store.state.item.nome" @change="atualizarCliente($event, 'nome')">
                </input-container>
            </template>
            <template v-slot:rodape>
                <div class="d-flex justify-content-between" style="width: 100%;">
                    <button type="button" class="btn btn-primary" v-on:click="atualizar()" >Atualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearModalFields()">Cancelar</button>
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
                urlBase: 'http://localhost:8000/api/v1/cliente',
                urlPaginacao: '',
                urlFiltro: '',
                nome: '',
                transacaoStatus: '',
                transacoesDetalhes: {},
                clientes: {data: []},
                busca: { id: '', nome: ''},
                isModalClosed: this.$store.state.isModalOpened,
            }
        },
        methods: {
            atualizarCliente(e, field) {
                switch (field) {
                    case 'nome':
                        this.nome = e.target.value;
                        break;
                    default:
                        console.log('Wrong selection');
                        break;
                }
            },
            paginacao(l) {
                if (l.url) {
                    this.urlPaginacao = l.url.split('?')[1];
                    this.carregarLista();
                }
            },
            clearStore() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                this.$store.state.transacao.dados = '';
                this.$store.state.item = {};
            },
            clearModalFields() {
                this.nome = '';
                this.clearStore();
            },
            carregarLista() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
                axios.get(url)
                .then(response => {
                    this.clientes = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            salvar() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                let objCreate = {};
                objCreate['nome'] = this.nome;
                let config = {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                }
                axios.post(this.urlBase, objCreate, config)
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
            remover() {
                let url = this.urlBase+'/'+this.$store.state.item.id;
                axios.delete(url)
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
            atualizar() {
                let url = this.urlBase+'/'+this.$store.state.item.id;
                let objUpdate = {};
                if (this.nome) {
                    objUpdate['nome'] = this.nome;
                } else {
                    objUpdate['nome'] = this.$store.state.item.nome;
                }   
                let config = {
                    headers: {
                        'Content-Type': 'application/json',
                    }
                };
                axios.put(url, objUpdate, config)
                .then((response) => {
                    this.$store.state.transacao.mensagem = 'Registro atualizado!';
                    this.$store.state.transacao.status = 'atualizar';
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.mensagem = '';
                    this.$store.state.transacao.dados = errors.response.data.errors;
                    this.$store.state.transacao.status = 'erro';
                });
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
        },
        watch: {
            isModalClosed(e) {
                if (!this.isModalClosed) {
                    this.clearModalFields();
                } 
            }
        }
    }
</script>