<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 my-3">
                    <p>Here a list of your Clients</p>
                     @foreach ($clients as $client)
                      @if ($client->revoked == 0)
                         <div class="py-3 text-gray-900">
                            <p ><b>Client Name </b>{{ $client->name }}</p>
                             <p><b>Client Id </b> : {{ $client-> id }}</p>
                             <p><b>Client Redirect</b> : {{ $client-> redirect }}</p>
                             <p><b>Client Secret</b> : {{ $client-> secret }}</p>
                             
                             <form action="/oauth/clients/{{$client->id}}" method="POST">
                                 @csrf
                                 @method('DELETE')
                                 <x-button>Delete Client</x-button>
                             </form>
                         </div>
                      @endif
                     @endforeach
                </div> <hr>
                <div class="p-6 bg-white border-b border-gray-200 my-3">
                    <p>Here a list of your Tokens</p>
                    
                <div class="py-3 text-gray-900">
                @foreach ($tokens as $token)
                    @if ($token->revoked == 0)
                        
                    <p ><b>Name </b>{{ $token->client->name }}</p>
                    <p ><b>Redirect Url </b>{{ $token->client->redirect }}</p>
                    <div class="inline-flex">
                        <form method="post" action="/oauth/tokens/{{$token->id}}">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit">Revoke Token</x-button>
                        </form>
                    </div>

                    @endif
                    
                @endforeach
                </div> <hr>
                
                <h1>Create Client</h1>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="/oauth/clients" method="POST">
                        <div>
                            <x-label for="name">Name</x-label>
                            <x-input type="text" name="name" placeholder="Client Name" ></x-input>
                        </div>
                        <div class="mt-2">
                            <x-label for="name">Redirect</x-label>
                            <x-input type="text" name="redirect" placeholder="Client Callback Url" ></x-input>
                        </div>
                        <div class="mt-3">
                            @csrf
                            <x-button type="submit">Create Client</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
