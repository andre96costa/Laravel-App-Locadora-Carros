<template>
    <div v-bind:class="estilo" role="alert">
        {{ titulo }}
        <hr>
        <p>{{ detalhes.mensagem }}</p>
        <br>
        <ul v-if="detalhes.dados">
            <li v-for="e, key in detalhes.dados" v-bind:key="key">
                {{ e[0] }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['tipo', 'detalhes', 'titulo'],
    computed: {
        estilo() {
            return "alert alert-"+this.tipo
        },
    }
}
</script>