class App {
    constructor() {
        this.init();
    }

    async init() {
        let forms = document.querySelectorAll("form");
        
        forms.forEach(form => {
            form.addEventListener("submit", async function(e) {
                e.preventDefault();
                let query = [];
                form.querySelectorAll("input").forEach(field => {
                    query.push(field.name + "=" + field.value);
                });
                console.log(await app.getJson(`${form.action}?${query.join("&")}`));
            });
        });
    }

    async getJson(url) {
        const promise = await fetch(url);
        return await promise.json();
    }

}