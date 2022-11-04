<template>
  <main class="form-signin w-100 h-100 m-auto d-flex  justify-content-center align-items-center">
    <div>
      <div class="text-center">
        <svg xmlns="http://www.w3.org/2000/svg" height="70" class="mr-2" viewBox="0 0 512 512"
             style="enable-background:new 0 0 512.001 512.001" xml:space="preserve"><path d="m378 104-28-19a8 8 0 0 0-9 13l28 20c4 3 9 2 12-2 2-4 1-9-3-12zM435 143l-28-19a8 8 0 0 0-10 14l29 19c3 3 9 2 11-2 3-4 2-9-2-12zM167 134l-34-7a8 8 0 0 0-3 16l33 8h2a8 8 0 0 0 2-17zM234 150l-33-8a8 8 0 0 0-4 17l33 7 2 1a8 8 0 0 0 2-17zM247 190l-76-18a8 8 0 0 0-4 16l68 16-55 234-126-30 54-234 26 6a8 8 0 0 0 4-16l-34-8c-4-1-9 2-10 6L35 412c-1 5 2 9 7 10l143 34a8 8 0 0 0 10-6l58-250c2-5-1-9-6-10z"/>
          <path
            d="M204 284c-1-3-4-5-8-5l-31 2-12-28a8 8 0 0 0-16 1l-7 30-32 3a8 8 0 0 0-3 16l27 16-8 30a8 8 0 0 0 14 9l24-21 27 16a8 8 0 0 0 12-10l-13-29 24-20c3-3 4-7 2-10zm-43 31 4 11-10-6c-7-5-14 4-18 8l2-11c1-4 0-8-4-9l-10-6 12-1c8-1 9-12 10-18l5 11c3 7 14 4 20 4-5 4-15 9-11 17z"/>
          <path
            d="M508 128c-7-5-9-15-5-23 2-3 1-8-3-10l-43-30c-3-2-8-2-11 1a17 17 0 0 1-28-19c2-4 1-8-2-11L373 7c-4-3-8-2-11 1a17 17 0 0 1-23 2c-4-2-9-1-11 3l-49 69-2-1-51-12c-4-1-8 2-10 5-3 8-11 13-20 11-8-2-14-10-13-18 0-5-2-8-6-9l-51-12c-4-1-8 1-10 5-3 8-12 13-20 11-4-1-9 1-10 6l-12 53L0 434c-1 4 2 9 6 10 9 2 15 10 14 18-1 4 2 8 6 9l51 12c4 1 8-1 9-5 7-18 36-12 34 8 0 4 2 8 6 9l51 12c4 1 8-2 10-6 2-6 9-11 16-11 3 0 7 2 10 0l4-5 11-47 23 16c4 2 8 2 11-1a17 17 0 0 1 23-3c4 3 9 2 12-2l214-309c2-4 1-9-3-11zM227 368l-13 54-12 52c-11 0-21 6-28 15l-38-9c-1-14-12-26-26-29-13-3-28 3-36 14l-38-9c-1-11-8-20-18-26l70-297 8 2h2a8 8 0 0 0 2-16l-8-2 9-38c11 0 21-6 27-15l38 8c2 14 12 26 26 29 14 4 29-2 37-14l38 9v2a34 34 0 0 0 18 24l-9 38-8-2a8 8 0 0 0-4 16l8 2-13 57-2 6-9 42-21 87zm243-200-6-5a8 8 0 0 0-10 14l7 5-174 251a34 34 0 0 0-31 4l-24-17 8-36 37 26c4 2 9 1 12-2l107-155a8 8 0 1 0-13-10L280 391l-36-24 17-70 29-10 19 25a8 8 0 0 0 15-5l-1-32 30-10a8 8 0 0 0 0-16l-30-9-1-31a8 8 0 0 0-15-5l-18 26-12-3 22-92 10-15 107 74-14 21a8 8 0 1 0 14 10l19-28c3-4 2-9-2-12l-121-84c-4-2-9-1-12 2l-3 6h-1c-4-1-7-4-10-7l19-28 7 5c4 2 9 1 12-2 3-4 2-9-2-12l-7-5 22-32a34 34 0 0 0 32-4l32 22c-4 14 1 29 13 37s28 8 39 0l32 22c-2 11 0 22 8 31l-23 32zM307 281l-7-9c-2-3-6-4-10-3l-11 4c4-5 12-13 7-19l-7-10 11 4c8 2 12-8 16-13v12c0 8 11 9 17 11l-11 4c-3 1-6 4-5 8v11z"/></svg>

        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
      </div>

      <AuthLogin class="shadow-lg" style="width: 500px" />

      <LayoutFooter />
    </div>
  </main>
</template>

<script>
export default {
  layout: 'empty'
}
</script>

<style type="text/css">
html,
body {
  height: 100%;
  background: whitesmoke;
}

.form-signin {

}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
