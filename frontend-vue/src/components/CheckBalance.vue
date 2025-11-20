<template>
  <div style="margin-top:1rem; padding: .5rem; border: 1px solid #ddd">
    <h2>Consultar balance</h2>
    <form @submit.prevent="submit">
      <div style="margin-bottom:.5rem">
        <label for="check-document">Documento</label>
        <input id="check-document" v-model="form.document" placeholder="document" />
      </div>
      <div style="margin-bottom:.5rem">
        <label for="check-phone">Teléfono</label>
        <input id="check-phone" v-model="form.phone" placeholder="phone" />
      </div>
      <button>Consultar</button>
    </form>
    <pre>{{ response }}</pre>
  </div>
</template>

<script>
import api from '../api'
export default {
  data() {
    return { form: { document: '', phone: '' }, response: null }
  },
  methods: {
    async submit() {
      try {
        const res = await api.post('/api/checkBalance', this.form)
        this.response = res.data
      } catch (e) {
        this.response = e.response ? e.response.data : String(e)
      }
    }
  }
}
</script>
