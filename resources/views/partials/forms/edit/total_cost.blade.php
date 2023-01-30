<!-- Total Cost -->
<div class="form-group {{ $errors->has('total_cost') ? ' has-error' : '' }}">
    <label for="total_cost" class="col-md-3 control-label">{{ trans('general.total_cost') }}</label>
    <div class="col-md-9">
        <div class="input-group col-md-4" style="padding-left: 0px;">
            <input readonly class="form-control" type="text" name="total_cost" aria-label="total_cost" id="total_cost" value="{{ old('total_cost', Helper::formatCurrencyOutput($item->total_cost)) }}" />
            <span class="input-group-addon">
                @if (isset($currency_type))
                    {{ $currency_type }}
                @else
                    {{ $snipeSettings->default_currency }}
                @endif
            </span>
        </div>
        <div class="col-md-9" style="padding-left: 0px;">
            {!! $errors->first('total_cost', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
        </div>
    </div>

</div>
