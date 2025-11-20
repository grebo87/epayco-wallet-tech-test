<template>
  <div style="font-family: Arial, sans-serif; padding: 1rem;">
    <h1>Epayco Wallet — Demo cliente</h1>
    <RegisterClient />
    <CheckBalance />
    <RechargeWallet />
    <PayWallet />
    <ConfirmPayment />
  </div>
</template>

<script>
import RegisterClient from './components/RegisterClient.vue'
import CheckBalance from './components/CheckBalance.vue'
import RechargeWallet from './components/RechargeWallet.vue'
import PayWallet from './components/PayWallet.vue'
import ConfirmPayment from './components/ConfirmPayment.vue'

export default {
  components: { RegisterClient, CheckBalance, RechargeWallet, PayWallet, ConfirmPayment }
}
</script>
