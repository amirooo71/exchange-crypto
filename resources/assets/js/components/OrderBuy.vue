<template>
    <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="errors.clear($event.target.name)">
        <div class="panel ng-bg-dark">
            <div class="panel-heading">
                <h5 class="panel-title text-center">خرید</h5>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div>
                        <input type="text" class="form-control" name="price" v-model="price" placeholder="قیمت">
                        <span class="text-danger help-block" v-if="errors.has('price')"
                              v-text="errors.get('price')"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <input type="text" class="form-control" name="amount" v-model="amount" placeholder="مقدار">
                        <span class="text-danger help-block" v-if="errors.has('amount')"
                              v-text="errors.get('amount')"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <input type="text" class="form-control" :value="price * amount" placeholder="جمع کل">
                    </div>
                </div>
                <div class="form-group">
                    <div class="text-right">
                        <button type="submit" class="btn btn-success btn-block" :disabled="errors.any()">خرید</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>

    class Errors {

        constructor() {
            this.errors = {};
        }

        get(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        }

        has(field) {
            return this.errors.hasOwnProperty(field);
        }

        any() {
            return Object.keys(this.errors).length > 0;
        }

        record(errors) {
            this.errors = errors;
        }

        clear(field) {
            delete this.errors[field];
        }

    }

    export default {
        name: "order-buy",

        data() {
            return {
                price: '',
                amount: '',
                currency_id: 1,
                errors: new Errors(),
            }
        },

        methods: {

            onSubmit() {
                axios.post('api/v1/trade/orderbuy ', this.$data)
                    .then(this.onSuccess)
                    .catch(error => {
                        this.errors.record(error.response.data)
                        if (error.response.status == 403) {
                            this.onError();
                        }
                    })
                ;
            },

            onSuccess(response) {
                notify('success', 'سفارش خرید با موفقیت ثبت شد.');
                this.price = '';
                this.amount = '';
                Event.$emit('orderApplied');
            },

            onError() {
                notify('error', 'موجودی کافی نیست.');
                this.amount = '';
            }

        }
    }
</script>

<style scoped>

</style>