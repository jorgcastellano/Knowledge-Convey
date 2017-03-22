<div class="show_if_ticket-event">
    <p class="form-field _enable_reduce_ticket_field ">
        <label for="_enable_reduce_ticket"><?php echo __('Enable reduced-price', 'yith-event-tickets-for-woocommerce') ?></label>
        <input  id="_enable_reduce_ticket"
                type="checkbox"
                class="yith-wceti-reduceticket"
                style="" name="_reduce_ticket[_enable]"
                <?php if(isset($reduce_ticket['_enable'])){if('on' == $reduce_ticket['_enable']){ echo 'checked';}}?>
        >
    </p>
    <div class="yith-woocommerce-event-tickets-panel <?php if(isset($reduce_ticket['_enable'])){if('on' != $reduce_ticket['_enable']){ echo 'hidden';}}?>">
        <div class="yith-woocommerce-event-tickets-options">
            <p class="form-field _reduce_ticket_event_type_field ">
                <label for="_reduce_ticket_event_type"><?php echo __('Select price reduction type', 'yith-event-tickets-for-woocommerce') ?></label>
                <select
                    id="_reduce_ticket_event_type"
                    name="_reduce_ticket[_event_type]"
                    class="yith-wceti-reduceticket-type" style="">
                    <option value="fixed"
                            <?php if(isset($reduce_ticket['_event_type'])){if('fixed' == $reduce_ticket['_event_type']){ echo 'selected';}}?>
                    ><?php echo __('Fixed', 'yith-event-tickets-for-woocommerce') ?></option>
                    <option value="percentage"
                            <?php if(isset($reduce_ticket['_event_type'])){if('percentage' == $reduce_ticket['_event_type']){ echo 'selected';}}?>
                    ><?php echo __('Percentage', 'yith-event-tickets-for-woocommerce') ?></option>
                </select>
            </p>
            <p class="form-field _price_reduced_fixed_field " style="">
                <label for="_price_reduced_fixed"><?php echo __('Fixed amount', 'yith-event-tickets-for-woocommerce'). ' (' .
                        get_woocommerce_currency_symbol() . ')' ?></label>
                <input type="text"
                       class="yith-wceti-reduceticket-fixed wc_input_price"
                       style=""
                       name="_reduce_ticket[_price_fixed]"
                       id="_price_reduced_fixed"
                       value="<?php if(isset($reduce_ticket['_price_fixed'])){echo $reduce_ticket['_price_fixed'];} ?>"
                       placeholder="">
            </p>
            <p class="form-field _price_relative_fixed_field " style="">
                <label for="_price_relative_fixed"><?php echo __('Percent amount', 'yith-event-tickets-for-woocommerce'). ' (%)' ?></label>
                <input type="number"
                       class="yith-wceti-reduceticket-fixed"
                       style=""
                       name="_reduce_ticket[_price_relative]"
                       id="_price_relative_fixed"
                       value="<?php if(isset($reduce_ticket['_price_relative'])){echo $reduce_ticket['_price_relative'];} ?>"
                       step="any"
                       min="0" max="100">
            </p>
        </div>
        <div class="yith-woocommerce-event-tickets-description">
            <p class="form-field _reduce_ticket_description_field ">
                <label for="_reduce_ticket_description"><?php echo __('Description for reduced-price tickets', 'yith-event-tickets-for-woocommerce')
                    ?></label>
                <textarea class="yith-wceti-reduceticket-description"
                          style=""
                          name="_reduce_ticket[_description]"
                          id="_reduce_ticket_description"
                          placeholder="" rows="2" cols="20"><?php if(isset($reduce_ticket['_description'])){echo $reduce_ticket['_description'];} ?></textarea>
            </p>
        </div>
    </div>
</div>