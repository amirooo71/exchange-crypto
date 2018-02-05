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
                    <td v-if="order.type == 'فروش'"><span class="icon-checkmark2 text-success"></span></td>
                    <td v-if="order.type == 'خرید'"><span class="icon-cross2 text-danger"></span></td>
                    <td>
                        <ul class="icons-list" style="color: #CFD8DC;">
                            <li>
                                <a @click="editOrder(order)">
                                    <i class="icon-pencil7"></i>
                                </a>
                            </li>
                            <li>
                                <a>
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
                        <form class="form-horizontal" @submit.prevent="onSubmit">
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
                                            <span class="text-danger help-block"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">مقدار:</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control js-order-amount" name="amount"
                                                   v-model="amount">
                                            <span class="text-danger help-block"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">کل مبلغ</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control js-order-total"
                                                   :value="price * amount">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success">ویرایش خرید <i
                                                class="icon-arrow-left13 position-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!--<div class="modal-footer">-->
                    <!--<button type="button" class="btn btn-link text-white" data-dismiss="modal">خروج</button>-->
                    <!--&lt;!&ndash;<button type="button" class="btn btn-primary text-white">Save changes</button>&ndash;&gt;-->
                    <!--</div>-->
                </div>
            </div>
        </div>
        <!-- /basic modal -->
    </div>

</template>

<script>

    window.Global_Orders = [];

    export default {

        name: "user-order",

        props: ['user'],

        data() {
            return {
                orders: [],
                price: '',
                amount: '',
                currency_id: 1,
                type: '',
                currentOrderId: '',
            }
        },

        created() {
            Event.$on('orderApplied', data => {
                data['color'] = "#78909C";
                this.orders.push(data);
                console.log(data);
            });
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

            getUserOrders() {
                var user = JSON.parse(this.user);
                axios.get('/api/v1/orders/' + user.id + '/history')
                    .then(response => {
                        this.orders = response.data;
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    })
                ;
            },

            editOrder(order) {
                this.currentOrderId = order.id;
                $('.js-order-price').val(order.price);
                $('.js-order-amount').val(order.amount);
                $('.js-order-total').val(order.amount * order.price);
                $("#modal_default").modal('show');
            },

            onSubmit() {
                axios.post('api/v1/trade/orderbuy/' + this.currentOrderId + '/edit', this.$data)
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error.response.data)
                    })
                ;
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