<template>
  <div style="margin-top:1rem; padding: .5rem; border: 1px solid #ddd">
    <h2>Registrar cliente</h2>
    <form @submit.prevent="submit">
      <div style="margin-bottom:.5rem">
        <label for="register-document">Documento</label>
        <input id="register-document" v-model="form.document" placeholder="document" />
      </div>
      <div style="margin-bottom:.5rem">
        <label for="register-name">Nombre</label>
        <input id="register-name" v-model="form.name" placeholder="name" />
      </div>
      <div style="margin-bottom:.5rem">
        <label for="register-email">Email</label>
        <input id="register-email" v-model="form.email" placeholder="email" />
      </div>
      <div style="margin-bottom:.5rem">
        <label for="register-phone">Teléfono</label>
        <input id="register-phone" v-model="form.phone" placeholder="phone" />
      </div>
      <button>Registrar</button>
    </form>
    <pre>{{ response }}</pre>
  </div>
</template>

<script>
import api from '../api'
export default {
  data() {
    return {
      form: { document: '', name: '', email: '', phone: '' },
      response: null
    }
  },
  methods: {
    async submit() {
      try {
        const res = await api.post('/api/registerClient', this.form)
        this.response = res.data
      } catch (e) {
        this.response = e.response ? e.response.data : String(e)
      }
    }
  }
}
</script>
