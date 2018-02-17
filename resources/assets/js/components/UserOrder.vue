<template>
    <div>
        <div class="table-responsive pre-scrollable">
            <table class="table">
                <thead>
                <tr class="v-bg-dark">
                    <th>نوع</th>
                    <th>میزان</th>
                    <th>قیمت</th>
                    <th>ارز</th>
                    <th>ساعت</th>
                    <th>وضعیت</th>
                    <th>ویرایش</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in sortedOrders" :style="{'background': order.color}">
                    <td>{{order.type}}</td>
                    <td>{{order.amount | round}}</td>
                    <td>{{order.price | currency}}</td>
                    <td>BTC</td>
                    <td>{{order.created_at}}</td>
                    <td v-if="order.status == 'confirmed'"><span class="icon-checkmark2 text-success"></span></td>
                    <td v-else><span class="icon-cross2 text-danger"></span>
                    </td>
                    <td>
                        <ul class="icons-list" style="color: #CFD8DC;">
                            <li>
                                <a @click="updateOrder(order)">
                                    <i class="icon-pencil7"></i>
                                </a>
                            </li>
                            <li>
                                <a @click="onRemove(order)">
                                    <i class="icon-trash"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="modal_default" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">ویرایش</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" @submit.prevent="onSubmit"
                              @keydown="errors.clear($event.target.name)">
                            <div class="panel ng-bg-dark">
                                <div class="panel-heading">
                                    <h5 class="panel-title">فروش</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">قیمت:</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control js-order-price" name="price"
                                                   v-model="price">
                                            <span class="text-danger help-block" v-if="errors.has('price')"
                                                  v-text="errors.get('price')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">مقدار:</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control js-order-amount" name="amount"
                                                   v-model="amount">
                                            <span class="text-danger help-block" v-if="errors.has('amount')"
                                                  v-text="errors.get('amount')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">کل مبلغ:</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control js-order-total"
                                                   :value="price * amount">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success" :disabled="errors.any()">تایید
                                            خرید <i
                                                    class="icon-arrow-left13 position-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /basic modal -->
    </div>

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

        name: "user-order",

        props: ['user'],

        data() {
            return {
                orders: [],
                price: '',
                amount: '',
                currency_id: 1,
                selectedOrder: {},
                uriAction: '',
                errors: new Errors(),
            }
        },

        created() {
            Event.$on('orderApplied', data => this.onOrderAppliedEvent(data));
        },

        mounted() {
            this.getUserOrders();
        },

        filters: {
            round(num) {
                return Math.round(num);
            },
        },

        methods: {

            onOrderAppliedEvent(data) {
                data['color'] = "#78909C";
                this.orders.push(data)
            },

            getUserOrders() {
                axios.get('/api/v1/trade/user/orders/history')
                    .then(response => this.orders = response.data)
                    .catch(error => console.log(error.response.data));
            },

            updateOrder(order) {
                this.selectedOrder = order;
                this.showModal();
            },

            onSubmit() {
                this.checkOrderType();
                axios.patch('api/v1/trade/' + this.uriAction + '/' + this.selectedOrder.id + '/update', this.$data)
                    .then(this.onSuccess)
                    .catch(error => this.errors.record(error.response.data))
                ;
            },

            showModal() {
                this.price = this.selectedOrder.price;
                this.amount = this.selectedOrder.amount;
                $("#modal_default").modal('show');
            },

            onSuccess(response) {
                this.removeOrder();
                response.data['color'] = "#26A69A";
                this.orders.push(response.data);
                this.price = '';
                this.amount = '';
                $("#modal_default").modal('hide');
                this.notify("سفارش با موفقیت ویرایش شد.")
            },


            checkOrderType() {
                if (this.selectedOrder.type == 'خرید') {
                    this.uriAction = "orderbuy";
                } else {
                    this.uriAction = "ordersell";
                }
            },

            removeOrder() {
                var index = this.orders.indexOf(this.selectedOrder);
                this.orders.splice(index, 1);
            },

            onRemove(order) {
                this.selectedOrder = order;
                this.checkOrderType();
                axios.delete('api/v1/trade/' + this.uriAction + '/' + this.selectedOrder.id + '/delete')
                    .then(() => {
                        this.removeOrder(order);
                        this.notify("سفارش با موفقیت حذف شد.");
                    });
            },

            notify(message) {
                new Noty({
                    type: 'success',
                    layout: 'bottomRight',
                    theme: 'mint',
                    text: message
                }).show();
            },

        },

        computed: {
            sortedOrders() {
                return _.orderBy(this.orders, ['id'], ['desc']);
            },
        },
    }
</script>

<style scoped>

</style>