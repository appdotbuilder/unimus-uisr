import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
                <header className="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>
                <div className="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                    <main className="flex w-full max-w-[335px] flex-col-reverse lg:max-w-4xl lg:flex-row">
                        <div className="flex-1 rounded-br-lg rounded-bl-lg bg-white p-6 pb-12 text-center shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-tl-lg lg:rounded-br-none lg:p-20 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                            <div className="mb-6">
                                <h1 className="mb-4 text-3xl font-bold">🎓 Unimus Intelligent System Repository</h1>
                                <p className="mb-6 text-lg text-[#706f6c] dark:text-[#A1A09A]">
                                    📊 Research dataset repository for Universitas Muhammadiyah Semarang
                                </p>
                            </div>
                            
                            <div className="mb-8 text-left max-w-2xl mx-auto">
                                <h2 className="text-xl font-semibold mb-4">🚀 Key Features</h2>
                                <div className="grid gap-4 text-sm">
                                    <div className="flex items-start gap-3">
                                        <span className="text-lg">📤</span>
                                        <div>
                                            <strong>Dataset Submission:</strong> Upload and manage research datasets with comprehensive metadata
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <span className="text-lg">✅</span>
                                        <div>
                                            <strong>Quality Curation:</strong> Professional review workflow ensuring dataset quality and standards
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <span className="text-lg">📖</span>
                                        <div>
                                            <strong>Citation Generator:</strong> APA and IEEE format citations for academic publications
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <span className="text-lg">📈</span>
                                        <div>
                                            <strong>Accreditation Reports:</strong> Track research output for institutional assessment
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200 dark:bg-blue-900/20 dark:border-blue-700">
                                <h3 className="text-lg font-semibold mb-2">🌍 Collaboration Support</h3>
                                <p className="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Track local, national, and international research collaborations with detailed reporting for academic accreditation
                                </p>
                            </div>

                            {!auth.user ? (
                                <div className="space-y-4">
                                    <p className="text-lg font-medium">Ready to contribute to research excellence?</p>
                                    <div className="flex gap-4 justify-center">
                                        <Link
                                            href={route('register')}
                                            className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                        >
                                            Join UISR
                                        </Link>
                                        <Link
                                            href={route('datasets.index')}
                                            className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                                        >
                                            Browse Datasets
                                        </Link>
                                    </div>
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    <p className="text-lg font-medium">Welcome back! 👋</p>
                                    <div className="flex gap-4 justify-center">
                                        <Link
                                            href={route('dashboard')}
                                            className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                        >
                                            Go to Dashboard
                                        </Link>
                                        <Link
                                            href={route('datasets.create')}
                                            className="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                                        >
                                            Submit Dataset
                                        </Link>
                                    </div>
                                </div>
                            )}

                            <footer className="mt-12 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                <p className="mb-2">🎯 Supporting academic excellence through data sharing</p>
                                <p>Universitas Muhammadiyah Semarang - Research Data Repository</p>
                            </footer>
                        </div>
                    </main>
                </div>
                <div className="hidden h-14.5 lg:block"></div>
            </div>
        </>
    );
}
