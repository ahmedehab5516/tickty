@extends('layouts.app')

@section('content')


<div class="container mt-5">
    <div class="profile-box shadow-sm">
        {{-- Profile Image --}}
        <div class="profile-image">
            <img src="{{ asset('images/profile-placeholder.jpg') }}" alt="Profile Photo">
        </div>

        {{-- Profile Info --}}
        <div class="profile-info">
            <h1 class="fw-bold mb-1">Hello!</h1>
            <p class="text-muted">I'm a registered user of Tickty.</p>

             
            <p>
                I love going to the movies and managing my bookings with ease. Whether it’s action, drama, or thriller — I’m always up for a good film night.
            </p>

            {{-- User Details --}}
            <div class="profile-details mt-4">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst(optional($user->role)->name ?? 'User') }}</p>
                <p><strong>Joined:</strong> {{ $user->created_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Bookings Summary --}}
    <div class="summary-section mt-4">
        <h5 class="fw-bold mb-2">🎟️ Bookings Summary</h5>
        <p>You’ve made <strong>{{ $user->bookings->count() }}</strong> ticket bookings so far.</p>
    </div>

{{-- Saved Cards Section --}}
<div class="summary-section mt-4">
    <h5 class="fw-bold mb-2">💳 Saved Cards</h5>
    @if($user->payments->whereNotNull('card_last4')->count())
        <ul class="list-group list-group-flush">
            @foreach($user->payments->whereNotNull('card_last4')->unique('card_last4') as $index => $card)
                <li class="list-group-item">
                    <a href="#" class="text-decoration-none text-dark"
                       data-bs-toggle="modal"
                       data-bs-target="#cardModal{{ $index }}">
                        • {{ ucfirst($card->card_brand ?? 'Card') }} ending in <strong>{{ $card->card_last4 }}</strong>
                    </a>

                    <!-- Modal -->
                    <div class="modal fade" id="cardModal{{ $index }}" tabindex="-1" aria-labelledby="cardModalLabel{{ $index }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow-sm">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="cardModalLabel{{ $index }}">💳 Card Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Card Brand:</strong> {{ ucfirst($card->card_brand ?? 'N/A') }}</p>
                                    <p><strong>Last 4 Digits:</strong> {{ $card->card_last4 }}</p>
                                
                                  
                                   
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">No saved cards yet.</p>
    @endif
</div>

</div>
@endsection

