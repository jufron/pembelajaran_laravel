<x-layout title="{{ $title }}">
  <x-slot:content>
    <div class="container my-4">
      @isset($error)
      <div class="row">
        <div class="alert alert-danger" role="alert">
          {{ $error }}
        </div>
      </div>
      @endisset
      <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
          <h1 class="display-4 fw-bold lh-1 mb-3">Create Todolist</h1>
          <p class="col-lg-10 fs-4">by <a target="_blank" href="https://www.programmerzamannow.com/">Programmer Zaman Now</a></p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
          <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="{{ route('todolist.store') }}">
            @csrf
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="todo" placeholder="todo">
              <label for="todo">Todo</label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Add Todo</button>
          </form>
        </div>
      </div>
    </div>
  </x-slot>
</x-layout>
