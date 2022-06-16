<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Buscar Carro">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="idInput" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do Modelo.">
                                    <input v-model="busca.id" type="number" class="form-control" id="idInput" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component  titulo="Placa" id="placaInput" id-help="placaInputHelp" texto-ajuda="Opcional. Informe a placa.">
                                    <input v-model="busca.placa" type="text" class="form-control" id="placaInput" aria-describedby="placaInputHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Lista dos Carros">
                    <template v-slot:conteudo>
                        <table-component 
                            v-bind:dados="carros.data" 
                            v-bind:visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#carroModalVisualizar'}"
                            v-bind:atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#carroModalAtualizar'}" 
                            v-bind:remover="{visivel: true, dataToggle: 'modal', dataTarget: '#carroModalRemover'}"
                            v-bind:titulos="{
                                id: {titulo: 'ID', tipo: 'text', show: true},
                                placa: {titulo: 'Placa', tipo: 'text', show: true},
                                disponivel: {titulo: 'Disponivel', tipo: 'checkbox', show: true},
                                km: {titulo: 'Km', tipo: 'text', show: true},
                                created_at: {titulo: 'Data Inserção', tipo: 'data', show: true},
                                modelo_id: {titulo: 'modelo_id', tipo: 'number', show: false},
                                modelo: {titulo: 'modelo', tipo: 'text[]', show: false},
                            }"
                        ></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div></div>
                        <paginate-component>
                            <li v-for="(l, key) in carros.links" :key="key" v-bind:class="l.active ? 'page-item active' : 'page-item'">
                                <a class="page-link" href="#" v-html="l.label" v-on:click="paginacao(l)" ></a>
                            </li>
                        </paginate-component>
                        <button type="button" class="btn btn-primary btn-sm float-right align-self-end" style="height: 28px;" data-bs-toggle="modal" data-bs-target="#carroModal" @click="clearModalFields()">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- MODAL INCLUSAO DE CARRO -->
        <modal-component id="carroModal" title="Adicionar Novo Carro" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso" v-if="$store.state.transacao.status == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao cadastrar modelo" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="Carros" id="placaInput">
                    <input type="text" class="form-control" id="placaInput" placeholder="Placa" v-model="placa">
                </input-container-component>  
                <input-container-component titulo="Carros" id="kmInput">
                    <input type="number" class="form-control" id="kmInput" placeholder="Kilometragem" v-model="km">
                </input-container-component>
                <input-container-component titulo="Disponivel" id="disponivelInput">
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="disponivelInput" id="disponivelInputSim" value="1" v-model="disponivel">
                                <label class="form-check-label" for="disponivelInputSim">
                                    Sim
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="disponivelInput" id="disponivelInputNao" value="0" v-model="disponivel">
                                <label class="form-check-label" for="disponivelInputNao">
                                    Não
                                </label>
                            </div>
                </input-container-component>
                <input-container-component titulo="Modelo do carro" id="modeloSelectInput">
                    <select class="form-select" aria-label="selecao do modelo" v-model="modelo_id">
                        <option value="">Selecione uma marca</option>
                        <option v-for="(v) in modelo.data" :key="v.id" :value="v.id">{{ v.nome }}</option>
                    </select>
                </input-container-component> 
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="salvar()" >Adicionar</button>
            </template>
        </modal-component>

        <!-- MODAL VISUALIZAR DADOS CARRO -->
        <modal-component id="carroModalVisualizar" title="Dados do carro" >
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-4">
                        <div class="row" v-if="$store.state.item.modelo">
                            <div class="col-12">
                                <input-container titulo="Modelo">
                                    <input type="text" class="form-control" v-bind:value="$store.state.item.modelo.nome" disabled>
                                </input-container>
                            </div>
                            <div class="col-12">
                                <input-container titulo="">
                                    <img v-bind:src="'/storage/'+$store.state.item.modelo.imagem" :alt="'imagem-'+$store.state.item.modelo.nome" v-if="$store.state.item.modelo.imagem" style="width: 100%;">
                                </input-container>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-4">
                                <input-container titulo="ID">
                                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                                </input-container>
                            </div>
                            <div class="col-8">
                                <input-container titulo="Placa">
                                        <input type="text" class="form-control" v-bind:value="$store.state.item.placa" disabled>
                                </input-container>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input-container titulo="Disponivel para locação?">
                                    <br>
                                    <span v-if="$store.state.item.disponivel">Sim</span>
                                    <span v-else>Não</span>
                                </input-container>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
            </template>
        </modal-component>

        <!-- MODAL DE REMOÇÃO DE CARRO -->
        <modal-component id="carroModalRemover" title="Remover Carro" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Remoção de registro" v-if="$store.state.transacao.status == 'removido'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao remover o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'removido'">
                <span>Tem certeza que deseja remover está carro?</span>
                <input-container titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                </input-container>
                <input-container titulo="Placa">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.placa" disabled>
                </input-container>
            </template>
            <template v-slot:rodape>
                <div class="d-flex justify-content-between" style="width: 100%;">
                    <button type="button" class="btn btn-danger" v-on:click="remover()" v-if="$store.state.transacao.status != 'removido'">Remover</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </template>
        </modal-component>

        <!-- MODAL DE ATUALIZACAO DO CARRO -->
        <modal-component id="carroModalAtualizar" title="Atualizar Carro" :data-modalIsOpen="isModalClosed = $store.state.isModalOpened">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização de registro" v-if="$store.state.transacao.status == 'atualizar'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao atualizar o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2">
                                <input-container titulo="ID">
                                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                                </input-container>
                            </div>
                            <div class="col-6">
                                <input-container titulo="Placa">
                                        <input type="text" class="form-control" :value="$store.state.item.placa" @change="atualizarCarro($event, 'placa')">
                                </input-container>
                            </div>
                            <div class="col-4">
                                <input-container titulo="Km">
                                        <input type="number" class="form-control" :value="$store.state.item.km" @change="atualizarCarro($event, 'km')">
                                </input-container>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input-container-component titulo="Diponivel para locação" id="disponivelInput">
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="disponivelInput" @change="atualizarCarro($event, 'disponivel')" id="disponivelInputSim" value="1" :checked="$store.state.item.disponivel == 1">
                                        <label class="form-check-label" for="absInputSim">
                                            Sim
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="disponivelInput" @change="atualizarCarro($event, 'disponivel')" id="disponivelInputNao" value="0" :checked="$store.state.item.disponivel == 0">
                                        <label class="form-check-label" for="disponivelInputNao">
                                            Não
                                        </label>
                                    </div>
                                </input-container-component>
                            </div>
                        </div>
                        <hr>
                        <div class="row" v-if="$store.state.item.modelo">
                            <div class="col-12">
                                <input-container-component titulo="Modelo do carro" id="modeloSelectInput">
                                    <select class="form-select" aria-label="selecao do modelo" @change="atualizarCarro($event, 'modelo')">
                                        <template v-for="(v) in modelo.data" >
                                            <option :key="v.id" :value="v.id" :selected="v.id == isSelected.id">{{ v.nome }}</option>
                                        </template>
                                    </select>
                                </input-container-component> 
                            </div>
                        </div>
                    </div>
                </div>
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
            this.carregarModelos();
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/carro',
                urlPaginacao: '',
                urlFiltro: '',
                placa: '',
                disponivel: '',
                km: '',
                modelo_id: '',
                transacaoStatus: '',
                transacoesDetalhes: {},
                carros: {data: []},
                modelo: {data: []},
                busca: { id: '', placa: ''},
                isModalClosed: this.$store.state.isModalOpened,
                objTemp: {}
            }
        },
        methods: {
            atualizarCarro(e, field) {
                switch (field) {
                    case 'placa':
                        this.placa = e.target.value;
                        break;
                    case 'km':
                        this.km = e.target.value;
                        break
                    case 'disponivel':
                        this.disponivel = e.target.value;
                        break
                    case 'modelo':
                        this.modelo_id = e.target.value;
                        break
                    default:
                        console.log('Wrong selection');
                        break;
                }
            },
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
                console.log(this.arquivoImagem[0].name);
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
                this.placa = '';
                this.km = '';
                this.disponivel = '';
                this.modelo_id = '';
                this.clearStore();
            },
            carregarModelos() {
                let url = 'http://localhost:8000/api/v1/modelo/lista';
                axios.get(url)
                .then(response => {
                    this.modelo.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            carregarLista() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
                axios.get(url)
                .then(response => {
                    this.carros = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            salvar() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                let objCreate = {};
                objCreate['placa'] = this.placa;
                objCreate['km'] = this.km;
                objCreate['disponivel'] = this.disponivel;
                objCreate['modelo_id'] = this.modelo_id;
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
                if (this.placa) {
                    objUpdate['placa'] = this.placa;
                } else {
                    objUpdate['placa'] = this.$store.state.item.placa;
                }   
                if (this.km) {
                    objUpdate['km'] = this.km;
                } else {
                    objUpdate['km'] = this.$store.state.item.km;
                }
                if (this.disponivel) {
                    objUpdate['disponivel'] = this.disponivel;
                } else {
                    objUpdate['disponivel'] = this.$store.state.item.disponivel;
                }
                if (this.modelo_id) {
                    objUpdate['modelo_id'] = this.modelo_id;
                } else {
                    objUpdate['modelo_id'] = this.$store.state.item.modelo_id;
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
        computed: {
            isSelected() {
                if (this.$store.state.item.marca_id) {
                    return {
                        id: this.$store.state.item.marca_id,
                        nome: this.$store.state.item.marca.nome,
                    };
                } else {
                    return 0;
                }
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