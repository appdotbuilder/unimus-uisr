import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Dataset {
    id: number;
    title: string;
    description: string;
    domain: string;
    task: string;
    collaboration_type: string;
    download_count: number;
    citation_count: number;
    published_at: string;
    user: {
        profile?: {
            first_name: string;
            last_name: string;
        };
        name: string;
    };
    primary_file?: {
        formatted_size: string;
        extension: string;
    };
}

interface Props {
    datasets: {
        data: Dataset[];
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        current_page: number;
        last_page: number;
    };
    domains: string[];
    filters: {
        search?: string;
        domain?: string;
        collaboration_type?: string;
    };
    [key: string]: unknown;
}

export default function DatasetIndex({ datasets, domains, filters }: Props) {
    const handleSearch = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        const formData = new FormData(e.currentTarget);
        const searchParams = {
            search: formData.get('search'),
            domain: formData.get('domain'),
            collaboration_type: formData.get('collaboration_type'),
        };
        
        router.get(route('datasets.index'), searchParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    return (
        <AppShell>
            <Head title="Dataset Repository" />
            
            <div className="max-w-7xl mx-auto px-4 py-8">
                <div className="mb-8">
                    <h1 className="text-3xl font-bold mb-4">📊 Dataset Repository</h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Discover and access research datasets from Universitas Muhammadiyah Semarang
                    </p>
                </div>

                {/* Search and Filter Form */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
                    <form onSubmit={handleSearch} className="space-y-4">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label className="block text-sm font-medium mb-2">Search</label>
                                <input
                                    type="text"
                                    name="search"
                                    defaultValue={filters.search}
                                    placeholder="Search datasets..."
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-2">Domain</label>
                                <select
                                    name="domain"
                                    defaultValue={filters.domain}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                >
                                    <option value="">All Domains</option>
                                    {domains.map((domain) => (
                                        <option key={domain} value={domain}>{domain}</option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-2">Collaboration</label>
                                <select
                                    name="collaboration_type"
                                    defaultValue={filters.collaboration_type}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                >
                                    <option value="">All Types</option>
                                    <option value="local">Local</option>
                                    <option value="national">National</option>
                                    <option value="international">International</option>
                                </select>
                            </div>
                            <div className="flex items-end">
                                <Button type="submit" className="w-full">
                                    Search
                                </Button>
                            </div>
                        </div>
                    </form>
                </div>

                {/* Dataset Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    {datasets.data.map((dataset) => (
                        <div key={dataset.id} className="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow">
                            <div className="p-6">
                                <div className="flex items-start justify-between mb-3">
                                    <span className="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-200">
                                        {dataset.domain}
                                    </span>
                                    <span className={`inline-block px-2 py-1 text-xs font-medium rounded ${
                                        dataset.collaboration_type === 'international' 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : dataset.collaboration_type === 'national'
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                    }`}>
                                        {dataset.collaboration_type === 'international' ? '🌍 International' :
                                         dataset.collaboration_type === 'national' ? '🏛️ National' : '🏢 Local'}
                                    </span>
                                </div>
                                
                                <h3 className="font-semibold text-lg mb-2 line-clamp-2">
                                    <Link
                                        href={route('datasets.show', dataset.id)}
                                        className="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                    >
                                        {dataset.title}
                                    </Link>
                                </h3>
                                
                                <p className="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-3">
                                    {dataset.description}
                                </p>
                                
                                <div className="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    <div>👤 {dataset.user.profile?.first_name} {dataset.user.profile?.last_name} || {dataset.user.name}</div>
                                    <div>🎯 {dataset.task}</div>
                                    {dataset.primary_file && (
                                        <div>📁 {dataset.primary_file.extension.toUpperCase()} • {dataset.primary_file.formatted_size}</div>
                                    )}
                                </div>
                                
                                <div className="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>📥 {dataset.download_count} downloads</span>
                                    <span>📝 {dataset.citation_count} citations</span>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Empty State */}
                {datasets.data.length === 0 && (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">📊</div>
                        <h3 className="text-xl font-semibold mb-2">No datasets found</h3>
                        <p className="text-gray-600 dark:text-gray-400">
                            Try adjusting your search criteria or browse all datasets.
                        </p>
                    </div>
                )}

                {/* Pagination */}
                {datasets.last_page > 1 && (
                    <div className="flex items-center justify-center space-x-2">
                        {datasets.links.map((link, index) => (
                            <button
                                key={index}
                                onClick={() => link.url && router.get(link.url)}
                                disabled={!link.url}
                                className={`px-3 py-2 text-sm rounded ${
                                    link.active 
                                        ? 'bg-blue-600 text-white' 
                                        : link.url 
                                        ? 'bg-white text-gray-700 border hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700'
                                        : 'bg-gray-100 text-gray-400 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500'
                                }`}
                            >
                                <span dangerouslySetInnerHTML={{ __html: link.label }} />
                            </button>
                        ))}
                    </div>
                )}
            </div>
        </AppShell>
    );
}