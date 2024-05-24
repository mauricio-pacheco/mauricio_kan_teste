import React, { useState } from 'react';

function FileUpload() {
    const [file, setFile] = useState(null);

    const handleFileChange = (e) => {
        setFile(e.target.files[0]);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('file', file);
        // Enviar formData para o backend
    };

    return (
        <div>
            <h2>Enviar Arquivo CSV</h2>
            <form onSubmit={handleSubmit}>
                <input type="file" onChange={handleFileChange} />
                <button type="submit">Enviar</button>
            </form>
        </div>
    );
}

export default FileUpload;
