<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Buscar Locação">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="idInput" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do cliente.">
                                    <input v-model="busca.id" type="number" class="form-control" id="idInput" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
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
                            v-bind:dados="locacao.data" 
                            v-bind:visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#locacaoModalVisualizar'}"
                            v-bind:atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#locacaoModalAtualizar'}" 
                            v-bind:remover="{visivel: false, dataToggle: 'modal', dataTarget: '#locacaoModalRemover'}"
                            v-bind:titulos="{
                                id: {titulo: 'ID', tipo: 'text', show: true},
                                data_inicio_periodo: {titulo: 'Data locação', tipo: 'data', show: true},
                                data_final_previsto_periodo: {titulo: 'Data devolução prevista', tipo: 'data', show: true},
                                valor_diaria: {titulo: 'Valor diaria', tipo: 'number', show: true},
                                km_inicial: {titulo: 'Km Inicial', tipo: 'number', show: true},
                                data_final_realizado_periodo: {titulo: 'Data devolução', tipo: 'data', show: true},
                                km_final: {titulo: 'Km final', tipo: 'number', show: true},
                                cliente_id: {titulo: 'Cliente', tipo: 'number', show: false},
                                carro_id: {titulo: 'Carro', tipo: 'number', show: false},
                                cliente: {titulo: 'cliente', tipo: 'text[]', show: false},
                                carro: {titulo: 'carro', tipo: 'text[]', show: false},
                            }"
                        ></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div></div>
                        <paginate-component>
                            <li v-for="(l, key) in locacao.links" :key="key" v-bind:class="l.active ? 'page-item active' : 'page-item'">
                                <a class="page-link" href="#" v-html="l.label" v-on:click="paginacao(l)" ></a>
                            </li>
                        </paginate-component>
                        <button type="button" class="btn btn-primary btn-sm float-right align-self-end" style="height: 28px;" data-bs-toggle="modal" data-bs-target="#locacaoModal" @click="clearModalFields()">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- MODAL INCLUSAO DE LOCACAO -->
        <modal-component id="locacaoModal" title="Adicionar Novo Cliente" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso" v-if="$store.state.transacao.status == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao cadastrar modelo" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="Clientes" id="clientesSelectInput">
                    <select class="form-select" aria-label="selecao de cliente" v-model="cliente_id">
                        <option value="" disabled>Selecione um cliente</option>
                        <option v-for="(v) in clientes.data" :key="v.id" :value="v.id">{{ v.nome }}</option>
                    </select>
                </input-container-component>
                <input-container-component titulo="Carros" id="carrosSelectInput">
                    <select class="form-select" aria-label="selecao de cliente" v-model="carro_id">
                        <option value="" disabled>Selecione um carro</option>
                        <option v-for="(v) in carros.data" :key="v.id" :value="v.id">{{ v.modelo.nome }}</option>
                    </select>
                </input-container-component> 
                <input-container-component titulo="Data locação" id="dataLocacaoInput">
                    <input type="text" class="form-control" id="dataLocacaoInput" placeholder="2000-10-20" v-model="data_inicio_periodo">
                </input-container-component>
                <input-container-component titulo="Data final previsto" id="dataFinalPrevistoInput">
                    <input type="text" class="form-control" id="dataFinalPrevistoInput" placeholder="2000-10-20" v-model="data_final_previsto_periodo">
                </input-container-component>
                <input-container-component titulo="Valor diaria" id="valorDiariaInput">
                    <input type="number" class="form-control" id="valorDiariaInput" placeholder="R$ 100" v-model="valor_diaria">
                </input-container-component>
                <input-container-component titulo="Km Inicial" id="kmInicialInput">
                    <input type="number" class="form-control" id="kmInicialInput" placeholder="10000" v-model="km_inicial">
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="salvar()" >Adicionar</button>
            </template>
        </modal-component>

        <!-- MODAL VISUALIZAR DADOS DA LOCACAO -->
        <modal-component id="locacaoModalVisualizar" title="Dados da Locação" >
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-4">
                        <input-container-component titulo="Numero locação" id="idInput">
                            <input type="number" class="form-control" id="idInput" placeholder="Id" :value="$store.state.item.id" disabled>
                        </input-container-component> 
                    </div>
                </div>
                <div class="row" v-if="$store.state.item.cliente && $store.state.item.carro">
                    <div class="col-4">
                        <template>
                            <img style="width: 100%;" :src="'/storage/'+$store.state.item.carro.modelo.imagem" :alt="'imagem/'+$store.state.item.carro.modelo.nome">
                        </template>
                    </div>
                    <div class="col-8">
                        <input-container-component titulo="Cliente" id="clienteNomeInput">
                            <input type="text" class="form-control" id="clienteNomeInput" :value="$store.state.item.cliente.nome" disabled>
                        </input-container-component>
                        <div class="row">
                            <div class="col-6">
                                <input-container-component titulo="Modelo" id="modeloInput">
                                    <input type="text" class="form-control" id="modeloInput" placeholder="Modelo" :value="$store.state.item.carro.modelo.nome" disabled>
                                </input-container-component> 
                            </div>
                            <div class="col-6">
                                <input-container-component titulo="Placa" id="placaIdInput">
                                    <input type="text" class="form-control" id="placaInput" placeholder="Placa" :value="$store.state.item.carro.placa" disabled>
                                </input-container-component> 
                            </div>
                        </div>
                        <input-container-component titulo="Data locação" id="dataLocacaoInput">
                            <input type="text" class="form-control" id="dataLocacaoInput" placeholder="Data inicio" :value="$store.state.item.data_inicio_periodo | formataDataTempoGlobal" disabled>
                        </input-container-component>
                        <input-container-component titulo="Data final previsto" id="dataFimPrevistoInput">
                            <input type="text" class="form-control" id="dataFimPrevistoInput" placeholder="Data fim previsto" :value="$store.state.item.data_final_previsto_periodo" disabled>
                        </input-container-component>
                        <input-container-component titulo="Valor diaria" id="valorDiariaInput">
                            <input type="number" class="form-control" id="valorDiariaInput" placeholder="Valor" :value="$store.state.item.valor_diaria" disabled>
                        </input-container-component>
                        <div class="row">
                            <div class="col-4">
                                <input-container-component titulo="Km Inicial" id="kmIncialInput">
                                    <input type="number" class="form-control" id="kmIncialInput" placeholder="Km Inicial" :value="$store.state.item.km_inicial" disabled>
                                </input-container-component>
                            </div>
                            <div class="col-4">
                                <input-container-component titulo="Km final" id="kmFinalInput">
                                    <input type="number" class="form-control" id="kmFinalInput" value="" disabled>
                                </input-container-component>
                            </div>
                        </div>
                        <input-container-component titulo="Data devolução" id="dataDevolucaoInput">
                            <input type="number" class="form-control" id="dataDevolucaoInput" value="" disabled>
                        </input-container-component>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
            </template>
        </modal-component>

        <!-- MODAL DE REMOÇÃO DE Cliente
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
        </modal-component> -->

        <!-- MODAL DE ATUALIZACAO DA LOCACAO -->
        <modal-component id="locacaoModalAtualizar" title="Finalizar Locação" :data-modalIsOpen="isModalClosed = $store.state.isModalOpened">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização de registro" v-if="$store.state.transacao.status == 'atualizar'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao atualizar o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <template  v-if="$store.state.item.cliente && $store.state.item.carro">
                    <div class="row">
                        <div class="col-6">
                            <input-container titulo="Numero Locação">
                                <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                            </input-container>
                        </div>
                        <div class="col-6">
                            <input-container-component titulo="Cliente" id="clienteNomeInput">
                                <input type="text" class="form-control" id="clienteNomeInput" :value="$store.state.item.cliente.nome" disabled>
                            </input-container-component>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input-container-component titulo="Modelo" id="modeloInput">
                                <input type="text" class="form-control" id="modeloInput" placeholder="Modelo" :value="$store.state.item.carro.modelo.nome" disabled>
                            </input-container-component> 
                        </div>
                        <div class="col-6">
                            <input-container-component titulo="Placa" id="placaIdInput">
                                <input type="text" class="form-control" id="placaInput" placeholder="Placa" :value="$store.state.item.carro.placa" disabled>
                            </input-container-component> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <input-container-component titulo="Valor diaria" id="valorDiariaInput">
                                <input type="number" class="form-control" id="valorDiariaInput" placeholder="Valor" :value="$store.state.item.valor_diaria" disabled>
                            </input-container-component>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input-container-component titulo="Km final" id="kmFinalInput">
                                <input type="number" class="form-control" id="kmFinalInput" v-model="km_final">
                            </input-container-component>
                        </div>
                        <div class="col-6">
                            <input-container-component titulo="Data devolução" id="dataDevolucaoInput">
                                <input type="text" class="form-control" id="dataDevolucaoInput" v-model="data_final_realizado_periodo">
                            </input-container-component>
                        </div>
                    </div>
                </template>
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
            this.carregarClientes();
            this.carregarCarros();
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/locacao',
                urlPaginacao: '',
                urlFiltro: '',
                cliente_id: '',
                carro_id: '',
                data_inicio_periodo: '',
                data_final_previsto_periodo: '',
                valor_diaria: '',
                km_inicial: '',
                data_final_realizado_periodo: '',
                km_final: '',
                transacaoStatus: '',
                transacoesDetalhes: {},
                locacao: {data: []},
                clientes: {data: []},
                carros: {data: []},
                busca: { id: '', nome: ''},
                isModalClosed: this.$store.state.isModalOpened,
            }
        },
        methods: {
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
                this.cliente_id = '',
                this.carro_id = '',
                this.data_inicio_periodo = '',
                this.data_final_previsto_periodo = '',
                this.valor_diaria = '',
                this.km_inicial = '',
                this.data_final_realizado_periodo = '',
                this.km_final = '',
                this.clearStore();
            },
            carregarClientes() {
                let url = 'http://localhost:8000/api/v1/cliente/lista';
                axios.get(url)
                .then(response => {
                    this.clientes.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            carregarCarros() {
                let url = 'http://localhost:8000/api/v1/carro/lista';
                axios.get(url)
                .then(response => {
                    this.carros.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            carregarLista() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
                axios.get(url)
                .then(response => {
                    this.locacao = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            salvar() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                let objCreate = {};
                objCreate['cliente_id'] = this.cliente_id;
                objCreate['carro_id'] = this.carro_id;
                objCreate['data_inicio_periodo'] = this.data_inicio_periodo;
                objCreate['data_final_previsto_periodo'] = this.data_final_previsto_periodo;
                objCreate['valor_diaria'] = this.valor_diaria;
                objCreate['km_inicial'] = this.km_inicial;
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
                objUpdate['cliente_id'] = this.$store.state.item.cliente_id;
                objUpdate['carro_id'] = this.$store.state.item.carro_id;
                objUpdate['data_inicio_periodo'] = this.$store.state.item.data_inicio_periodo.split(' ')[0];
                objUpdate['data_final_previsto_periodo'] = this.$store.state.item.data_final_previsto_periodo.split(' ')[0];
                objUpdate['valor_diaria'] = this.$store.state.item.valor_diaria;
                objUpdate['km_inicial'] = this.$store.state.item.km_inicial;
                objUpdate['data_final_realizado_periodo'] = this.data_final_realizado_periodo;
                objUpdate['km_final'] = this.km_final;
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