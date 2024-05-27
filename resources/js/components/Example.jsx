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
    const [updateFiles, setUpdateFiles] = useState(false); // Estado para controlar a atualização da lista

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
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/api/upload', true);

            // Configurando o evento de progresso
            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    const percentCompleted = Math.round((event.loaded * 100) / event.total);
                    setUploadProgress(percentCompleted);
                }
            };

            xhr.onload = async () => {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    setUploadMessage('Arquivo enviado com sucesso.');
                    setUpdateFiles(true); // Atualizando a lista de arquivos
                } else {
                    setUploadMessage('Erro ao enviar arquivo.');
                }
                setUploading(false);
            };

            xhr.send(formData);
        } catch (error) {
            console.error('Erro ao enviar arquivo:', error);
            setUploadMessage('Erro ao enviar arquivo.');
            setUploading(false);
        }
    };

    return (
        <div className="container">
            <div className="row">
                <div className="col-md-6 offset-md-3">
                    <br />
                    <h2 className="text-center">Carregar Arquivos .CSV</h2>
                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <input type="file" className="form-control" onChange={handleFileChange} />
                        </div>
                        <div className="text-center"> {/* Esta div é adicionada */}
                            <button type="submit" className="btn btn-primary btn-block">Carregar Arquivo</button>
                        </div>
                    </form>
                    {uploading && <PreLoader progress={uploadProgress} />}
                    {uploadMessage && <p>{uploadMessage}</p>}
                    <FileList files={files} updateFiles={updateFiles} />
                </div>
            </div>
            <div className="row">
                <div className="col-md-12 text-center">
                    <h6>Autor: Maurício Pacheco</h6>
                </div>
            </div>
        </div>
    );
}

export default Example;
