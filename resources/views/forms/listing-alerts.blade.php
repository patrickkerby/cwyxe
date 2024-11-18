<x-html-forms :form="$form" class="listing-alert">
  <input
    name="name"
    type="text"
    placeholder="Full Name"
    required
  >

  <input
    name="emailAddress"
    type="email"
    placeholder="Email Address"
    required
  >

  <input
    type="submit"
    value="Submit"
  />
</x-html-forms>
