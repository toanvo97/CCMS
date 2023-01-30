<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Labels</title>

</head>
<body>

<?php
$settings->labels_width = $settings->labels_width - $settings->labels_display_sgutter;
$settings->labels_height = $settings->labels_height - $settings->labels_display_bgutter;
// Leave space on bottom for 1D barcode if necessary
$qr_size = ($settings->alt_barcode_enabled=='1') && ($settings->alt_barcode!='') ? $settings->labels_height - .3 : $settings->labels_height - 0.1;
?>

<style>
    body {
        font-family: arial, helvetica, sans-serif;
        width: {{ $settings->labels_pagewidth }}in;
        height: {{ $settings->labels_pageheight }}in;
        margin: {{ $settings->labels_pmargin_top }}in {{ $settings->labels_pmargin_right }}in {{ $settings->labels_pmargin_bottom }}in {{ $settings->labels_pmargin_left }}in;
        font-size: {{ $settings->labels_fontsize }}pt;
    }
    /* .label:nth-child(n+1){
        margin-top: 0.2in;
    } */
   
    .label {
        width: 1.8in;
        height: 1.3in;
        padding: 0in;
        /* margin-right: 0.2in; */
        /* margin-bottom: 0.25in; */
        display: inline-block;
        overflow: hidden;
        margin-left: 5px;
        margin-right: 17px;
        margin-bottom: 16px;
        
    }
    .content-label {
        margin: 5px 5px;
        position: relative;
        min-height: 100px;
        margin-top: 15px;
    }

    .qr_text {
        float: inherit;
    }

    .page-break  {
        page-break-after:always;
    }
    div.qr_img {
        width: 0.85in;
        height: 0.85in;
        float: left;
        /* display: inline-flex; */
        /* padding-right: 0.15in; */
        /* margin-left: auto; */
    }
    img.qr_img {
        width: 100%;
        height: 100%;
        /* margin-top: -6.9%; */
        margin-left: -5px;
        padding-bottom: 0.04in;
        margin-right: auto;
    }
    img.barcode {
        display: block;
        /* padding-left: 0.05in; */
        /* padding-top: 0.11in; */
        width: 100%;
        text-align: center;
    }
    div.label-logo {
        float: right;
        display: inline-block;
    }
    img.label-logo {
        height: 0.5in;
    }
    .qr_text {
        float: inherit;
        /* width: 1.8in; */
        height: 1.15in;
        /* padding-top: 0.25in; */
        font-family: arial, helvetica, sans-serif;
        font-size: {{$settings->labels_fontsize}}pt;
        font-weight: 600;
        padding-right: 0.0001in;
        overflow: hidden !important;
        display: inline;
        word-wrap: break-word;
        word-break: break-all;
        min-height: 1.15in;
    }
    div.barcode_container {
        width: 100%;
        display: inline;
        overflow: hidden;
        /* position: absolute; */
        left: 0;
        right: 0;
        bottom: -10px;
    }
    .next-padding {
        margin: {{ $settings->labels_pmargin_top }}in {{ $settings->labels_pmargin_right }}in {{ $settings->labels_pmargin_bottom }}in {{ $settings->labels_pmargin_left }}in;
    }
    @media print {
        .noprint {
            display: none !important;
        }
        .next-padding {
            margin: {{ $settings->labels_pmargin_top }}in {{ $settings->labels_pmargin_right }}in {{ $settings->labels_pmargin_bottom }}in {{ $settings->labels_pmargin_left }}in;
            font-size: 0;
        }
    }
    @media screen {
        .label {
            outline: .02in black solid; /* outline doesn't occupy space like border does */
        }
        .noprint {
            font-size: 13px;
            padding-bottom: 15px;
        }
    }
    @if ($snipeSettings->custom_css)
        {{ $snipeSettings->show_custom_css() }}
    @endif
</style>
<div class="list-label">
@foreach ($assets as $asset)
    <?php $count++; ?>
    <div class="label">
        <div class="content-label">

        @if ($settings->qr_code=='1')
            <div class="qr_img">
                <img src="{{ config('app.url') }}/hardware/{{ $asset->id }}/qr_code" class="qr_img">
            </div>
        @endif

        <div class="qr_text">
            @if ($settings->label_logo)
                <div class="label-logo">
                    <img class="label-logo" src="{{ Storage::disk('public')->url('').e($snipeSettings->label_logo) }}">
                </div>
            @endif
            @if ($settings->qr_text!='')
                <div class="pull-left">
                    <strong>{{ $settings->qr_text }}</strong>
                    <br>
                </div>
            @endif
            @if (($settings->labels_display_company_name=='1') && ($asset->company))
                <div class="pull-left">
                    C: {{ $asset->company->name }}
                </div>
            @endif
            @if (($settings->labels_display_name=='1') && ($asset->name!=''))
                <div class="pull-left">
                    N: {{ $asset->name }}
                </div>
            @endif
            @if (($settings->labels_display_tag=='1') && ($asset->asset_tag!=''))
                <div class="pull-left">
                    T: {{ $asset->asset_tag }}
                </div>
            @endif
            @if (($settings->labels_display_serial=='1') && ($asset->serial!=''))
                <div class="pull-left">
                    S: {{ $asset->serial }}
                </div>
            @endif
            @if (($settings->labels_display_model=='1') && ($asset->model->name!=''))
                <div class="pull-left">
                    M: {{ $asset->model->name }} {{ $asset->model->model_number }}
                </div>
            @endif

        </div>

        @if ((($settings->alt_barcode_enabled=='1') && $settings->alt_barcode!=''))
            <div class="barcode_container">
                <img src="{{ config('app.url') }}/hardware/{{ $asset->id }}/barcode" class="barcode">
            </div>
        @endif

    </div>

    </div>

    @if ($count % $settings->labels_per_page == 0)
        <div class="page-break"></div>
        <div class="next-padding">&nbsp;</div>
    @endif

@endforeach
</div>

</body>
</html>
