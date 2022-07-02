window.onload = () => {
    const ui = SwaggerUIBundle({
        url: "/api/doc.json",
        dom_id: '#swagger-ui',
        validatorUrl: null,
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ],
        plugins: [
            SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: 'StandaloneLayout'
    });

    window.ui = ui;
};
