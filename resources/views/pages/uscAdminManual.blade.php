@extends('layout')

@section('title', 'USC Admin Manual')

@section('content')
    <div class="container">
        <h1 class="text-center manual-title">How to Process Orders in USC Admin</h1>
        <div class="manual-content">
            <p class="lead manual-intro">Here’s a step-by-step guide on how to process orders in the USC Admin panel. This guide is in Taglish (Tagalog-English) to make it easier for everyone to understand.</p>

            <div class="manual-step">
                <h3 class="step-title">1. Paki pindot ang menu button.</h3>
                <p class="step-description">First, click the menu button on the admin dashboard.</p>
                <!-- Adding the screenshot here -->
                <img src="{{ asset('images/menuScreenShot.PNG') }}" alt="Menu Button Screenshot" class="img-fluid step-image">
            </div>

            <div class="manual-step">
                <h3 class="step-title">2. Pa pindot po and "Transaction" sa menu</h3>
                <p class="step-description">Then, click on "Transaction" from the menu.</p>
                <img src="{{ asset('images/transactionLinkScreenShot.PNG') }}" alt="Transaction Link Screenshot" class="img-fluid step-image">
            </div>

            <div class="manual-step">
                <h3 class="step-title">3. Dito po makikita niyo po ang order at yung order status nila:</h3>
                <ul class="step-list">
                    <li><strong>Processing</strong> - Ito ang pinakaunang status ng order kapag ang customer ay nag-order.</li>
                    <li><strong>Packed</strong> - Kapag naibalot na ang mga damit at ready na po ito for shipment.</li>
                    <li><strong>Shipped</strong> - Kapag ang mga damit ay nasa delivery man o rider na, ready na po itong ide-deliver sa customer.</li>
                    <li><strong>Delivered</strong> - Kapag na-receive na po ng customer ang mga damit o ang order nila.</li>
                </ul>
                <img src="{{ asset('images/orderStatusScreenShot.PNG') }}" alt="Order Status Screenshot" class="img-fluid step-image">
            </div>

            <div class="manual-step">
                <h3 class="step-title">4. Kapag ipapa-receive na po sa customer ang order niya:</h3>
                <p class="step-description">Pakisabi po sa customer na buksan niya ang website natin: <strong><a href="https://store.ukayukaysupplier.com/">https://store.ukayukaysupplier.com/</a></strong></p>
                <p class="step-description">Pagkatapos, pakipindot po ang menu button at pumunta sa "Orders".</p>
                <img src="{{ asset('images/orderLinkStoreUsc.PNG') }}" alt="Order Link Screenshot" class="img-fluid step-image">
                <p class="step-description">Pakipindot po sa "Received Order" sa order na tumutugma po sa mga damit na dala ng rider.</p>
                <img src="{{ asset('images/storeUscOrder1.PNG') }}" alt="Store Order Screenshot" class="img-fluid step-image">
            </div>

            <div class="manual-step">
                <h3 class="step-title">5. In case na wala po ang phone ng customer:</h3>
                <p class="step-description">Kung wala po ang phone ng customer o kung hindi siya available, maaari po ninyong i-receive ang order sa admin site. Kailangan lang po ay kuhanan ng picture ang mga damit at kung sino po ang tumanggap ng order.</p>
                <p class="step-description">Pagkatapos, i-upload niyo po ang larawan ng mga damit at proof na natanggap ito.</p>
                <p class="step-description">Pakipindot po ang "Upload Proof" sa order. (Doble ingat lang po: Make sure na tama po ang order na inyong i-uploadan para iwas po sa mga conflict.)</p>

                <img src="{{ asset('images/uploadProof.PNG') }}" alt="Upload Proof Screenshot" class="img-fluid step-image">
            </div>

            <p class="alert alert-warning manual-warning">Tandaan po: Siguraduhing tama ang order na i-uploadan ng proof of receipt upang maiwasan ang mga pagkakamali.</p>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/manual.css?v=2.8') }}">
@endsection
