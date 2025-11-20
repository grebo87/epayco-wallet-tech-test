<template>
  <div style="margin-top:1rem; padding: .5rem; border: 1px solid #ddd">
    <h2>Pagar</h2>
    <form @submit.prevent="submit">
      <div style="margin-bottom:.5rem">
        <label for="pay-amount">Monto</label>
        <input id="pay-amount" v-model.number="form.amount" placeholder="amount" />
      </div>
      <div style="margin-bottom:.5rem">
        <label for="pay-document">Documento</label>
        <input id="pay-document" v-model="form.document" placeholder="document" />
      </div>
      <div style="margin-bottom:.5rem">
        <label for="pay-phone">Teléfono</label>
        <input id="pay-phone" v-model="form.phone" placeholder="phone" />
      </div>
      <button>Pagar</button>
    </form>
    <pre>{{ response }}</pre>
  </div>
</template>

<script>
import api from '../api'
export default {
  data() {
    return { form: { amount: 0, document: '', phone: '' }, response: null }
  },
  methods: {
    async submit() {
      try {
        const res = await api.post('/api/pay', this.form)
        this.response = res.data
      } catch (e) {
        this.response = e.response ? e.response.data : String(e)
      }
    }
  }
}
</script>
