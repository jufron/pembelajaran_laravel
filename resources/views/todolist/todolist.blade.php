<x-layout title="{{ $title }}">
  <x-slot:content>
    <div class="container my-4">
      <div class="row">
        <div class="col-md-6">
          <a href="{{ route('todolist.create') }}" class="btn btn-lg btn-primary">Create</a>
        </div>
        <div class="col-md-6">
          <form method="post" action="{{ route('todolist.logout') }}">
            @csrf
            <button class="w-15 btn btn-lg btn-danger" type="submit">Sign Out</button>
          </form>
        </div>
      </div>

      <div class="row align-items-right g-lg-5 py-5">
        <div class="mx-auto">

          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Todo</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($todolist as $todo)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $todo['todo'] }}</td>
                <td>
                  <form id="remove" action="{{ route('todolist.remove', $todo['id']) }}" method="post">
                    @csrf
                    @method('delete')
                    <button class="btn btn-lg btn-danger" type="submit">Remove</button>
                    <a href="{{ route('todolist.edit', $todo['id']) }}" class="btn btn-lg btn-warning text-white">Edit</a>
                  </form>
                </td>
              </tr>
              @empty
              <h1>Data Empty</h1>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </x-slot>
</x-layout>
