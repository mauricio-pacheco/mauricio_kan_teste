import React, { useState, useEffect } from 'react';
import PreLoader from './PreLoader';
import FileList from './FileList'; // Importe o componente FileList

function Example() {
    const [file, setFile] = useState(null);
    const [uploadMessage, setUploadMessage] = useState(null);
    const [uploading, setUploading] = useState(false);
    const [csrfToken, setCsrfToken] = useState('');
    const [uploadProgress, setUploadProgress] = useState(0); // Estado para armazenar o progresso do upload
    const [files, setFiles] = useState([]); // Estado para armazenar a lista de arquivos recebidos

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
                    setUploadProgress(percentCompleted); // Atualiza o progresso do upload
                }
            });

            if (response.ok) {
                const data = await response.json();
                setUploadMessage(`Arquivo enviado com sucesso. Caminho: ${data.path}`);
                
                // Atualiza a lista de arquivos após o upload bem-sucedido
                fetchFiles();
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

    // Função para buscar a lista de arquivos recebidos
    const fetchFiles = async () => {
        try {
            const response = await fetch('/api/files');
            const data = await response.json();
            setFiles(data.files); // Atualiza a lista de arquivos
        } catch (error) {
            console.error('Erro ao obter a lista de arquivos:', error);
        }
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <input type="file" onChange={handleFileChange} />
                <button type="submit">Carregar Arquivo</button>
            </form>
            {uploading && <PreLoader progress={uploadProgress} />} {/* Passa o progresso como prop para PreLoader */}
            {uploadMessage && <p>{uploadMessage}</p>}
            
            {/* Exibe a lista de arquivos */}
            <FileList files={files} />
        </div>
    );
}

export default Example;
