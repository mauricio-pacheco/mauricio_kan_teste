import React, { useState, useEffect } from 'react';

function FileList() {
    const [files, setFiles] = useState([]);

    useEffect(() => {
        async function fetchFiles() {
            try {
                const response = await fetch('/api/files');
                if (!response.ok) {
                    throw new Error('Erro ao obter a lista de arquivos.');
                }
                const data = await response.json();
                setFiles(data.files.map(file => {
                    // Formate o nome do arquivo removendo o caminho completo
                    return file.split('/').pop();
                }));
            } catch (error) {
                console.error('Erro ao obter a lista de arquivos:', error);
            }
        }

        fetchFiles();
    }, []);

    return (
        <div>
            <h2>Lista de Arquivos CSV</h2>
            <ul>
                {files.length > 0 ? (
                    files.map((file, index) => (
                        <li key={index}>{file}</li>
                    ))
                ) : (
                    <li>Nenhum arquivo encontrado.</li>
                )}
            </ul>
        </div>
    );
}

export default FileList;
