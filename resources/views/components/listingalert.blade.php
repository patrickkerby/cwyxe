<form action="" class="">
    <h4>Sign up for listing alerts</h4>
    <label>Name</label>
    <input type="text" placeholder="John Smith">
    <label>Email</label>
    <input type="text" placeholder="john@example.com">
    @if(is_singular('property'))
    <button class="buttoan btn-hollow">Submit</button>
    @else
    <button class="button">Submit</button>
    @endif
</form>