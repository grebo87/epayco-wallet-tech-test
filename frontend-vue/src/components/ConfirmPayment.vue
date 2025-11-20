<template>
  <div style="margin-top:1rem; padding: .5rem; border: 1px solid #ddd">
    <h2>Confirmar pago</h2>
    <form @submit.prevent="submit">
      <div style="margin-bottom:.5rem">
        <label for="confirm-idSession">ID Sesión</label>
        <input id="confirm-idSession" v-model="form.idSession" placeholder="idSession" />
      </div>
      <div style="margin-bottom:.5rem">
        <label for="confirm-token">Token</label>
        <input id="confirm-token" v-model="form.token" placeholder="token" />
      </div>
      <button>Confirmar</button>
    </form>
    <pre>{{ response }}</pre>
  </div>
</template>

<script>
import api from '../api'
export default {
  data() {
    return { form: { idSession: '', token: '' }, response: null }
  },
  methods: {
    async submit() {
      try {
        const res = await api.post('/api/confirmPayment', this.form)
        this.response = res.data
      } catch (e) {
        this.response = e.response ? e.response.data : String(e)
      }
    }
  }
}
</script>
