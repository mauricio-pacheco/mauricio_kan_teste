import React, { useState, useEffect } from 'react';
import PreLoader from './PreLoader';
import FileList from './FileList';

function Example() {
    const [file, setFile] = useState(null);
    const [uploadMessage, setUploadMessage] = useState(null);
    const [uploading, setUploading] = useState(false);
    const [csrfToken, setCsrfToken] = useState('');
    const [uploadProgress, setUploadProgress] = useState(0);
    const [files, setFiles] = useState([]);

    useEffect(() => {
        async function fetchCsrfToken() {
            const response = await fetch('/csrf-token');
            const data = await response.json();
            setCsrfToken(data.csrfToken);
        }
        fetchCsrfToken();
    }, []);

    const handleFileChange = (e) => {
        setFile(e.target.files[0]);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setUploading(true);

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('file', file);

        try {
            const response = await fetch('/api/upload', {
                method: 'POST',
                body: formData,
                onUploadProgress: (progressEvent) => {
                    const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    setUploadProgress(percentCompleted);
                }
            });

            if (response.ok) {
                const data = await response.json();
                setUploadMessage(`Arquivo enviado com sucesso. Caminho: ${data.path}`);
                setFiles(prevFiles => [...prevFiles, data.path.split('/').pop()]);
            } else {
                setUploadMessage('Erro ao enviar arquivo.');
            }
        } catch (error) {
            console.error('Erro ao enviar arquivo:', error);
            setUploadMessage('Erro ao enviar arquivo.');
        } finally {
            setUploading(false);
        }
    };

    return (
        <div className="container">
            <div className="row">
                <div className="col-md-6 offset-md-3">
                    <br></br>
                    <h2 className="text-center">Carregar Arquivos .CSV</h2>
                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <input type="file" className="form-control" onChange={handleFileChange} />
                        </div>
                        <button type="submit" className="btn btn-primary">Carregar Arquivo</button>
                    </form>
                    {uploading && <PreLoader progress={uploadProgress} />}
                    {uploadMessage && <p>{uploadMessage}</p>}
                    <FileList files={files} />
                </div>
            </div>
            <h6 className="text-center">Autor: Maurício Pacheco</h6>
        </div>
    );
}

export default Example;
