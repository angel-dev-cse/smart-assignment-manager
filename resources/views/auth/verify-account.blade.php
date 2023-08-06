<x-guest-layout>
<div class="card">
  <img class="card-img-top ml-5 mt-3" src="{{ asset('startheme/images/verification.png') }}" style="height:5rem;width:5rem" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">Welcome to <b>Smart Assignment Manager</b></h5>
    <p class="card-text">Hi <b>{{ Auth::user()->name }}</b>, <br/> Before accessing full functionality, you must be <b>Verified</b> by an <b>Administrator</b> </p>
    <br/>
    @if(Auth::user()->status === 'pending')
        <p class="card-text"><b>Verification Status :</b><button class="btn btn-warning btn-sm m-2">{{ Auth::user()->status }}</button></p>
    @else
        <p class="card-text"><b>Verification Status :</b><button class="btn btn-danger btn-sm m-2">{{ Auth::user()->status }}</button></p>
        <br/>
        <form method="POST" action="{{ route('verification.reapply') }}">
            @csrf
            <input type="hidden" name="id" id="id" value="{{ Auth::user()->id }}" />
            <input type="hidden" name="status" id="status" value="{{ Auth::user()->status }}" />
            <div class="text-center">
                <button class="btn btn-success btn-block">Reapply Verification</button>
            </div>
        </form>
    @endif
    
  </div>
</div>

<div class="mt-2 flex items-center justify-between">
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button class="btn btn-dark btn-sm">Sign Out</button>
    </form>
</div>
</x-guest-layout>
